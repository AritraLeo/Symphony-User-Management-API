<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/upload", name="api_upload", methods={"POST"})
     */
    public function upload(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        $file = $request->files->get('file');

        if (!$file) {
            return new Response('No file uploaded', Response::HTTP_BAD_REQUEST);
        }

        $filePath = $file->getPathname();
        $csv = array_map('str_getcsv', file($filePath));

        $header = array_shift($csv);

        foreach ($csv as $row) {
            $user = new User();
            $user->setName($row[0]);
            $user->setEmail($row[1]);
            $user->setUsername($row[2]);
            $user->setAddress($row[3]);
            $user->setRole($row[4]);

            $entityManager->persist($user);

            // $email = (new Email())
            //     ->from('noreply@example.com')
            //     ->to($row[1])
            //     ->subject('Welcome to Our Platform')
            //     ->text("Hello {$row[0]}, your data has been successfully stored!");

            // $mailer->send($email);
        }

        $entityManager->flush();

        return new Response('Data uploaded successfully', Response::HTTP_OK);
    }

    /**
 * @Route("/api/users", name="api_users", methods={"GET"})
 */
public function viewUsers(EntityManagerInterface $entityManager): Response
{
    $users = $entityManager->getRepository(User::class)->findAll();

    $data = [];
    foreach ($users as $user) {
        $data[] = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'address' => $user->getAddress(),
            'role' => $user->getRole(),
        ];
    }

    return $this->json($data);
}

/**
 * @Route("/api/backup", name="api_backup", methods={"GET"})
 */
public function backupDatabase(): Response
{
    $backupFile = 'backup.sql';
    $command = "mysqldump --user=db_user --password=db_password --host=127.0.0.1 db_name > $backupFile";
    system($command);

    return new Response('Database backup created', Response::HTTP_OK);
}

/**
 * @Route("/api/restore", name="api_restore", methods={"POST"})
 */
public function restoreDatabase(Request $request): Response
{
    $backupFile = $request->files->get('file');

    if (!$backupFile) {
        return new Response('No file uploaded', Response::HTTP_BAD_REQUEST);
    }

    $filePath = $backupFile->getPathname();
    $command = "mysql --user=db_user --password=db_password --host=127.0.0.1 db_name < $filePath";
    system($command);

    return new Response('Database restored from backup', Response::HTTP_OK);
}


}
