<?php

namespace App\Service;

use App\Entity\Ad;
use App\Entity\User;
use App\Entity\Booking;
use App\Entity\Comment;
use Doctrine\Common\Persistence\ManagerRegistry;

class StatsService
{
    private $manager;
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
        $this->manager = $managerRegistry->getManager();
    }

    public function getStats()
    {
        $users    = $this->getUsersCount();
        $ads      = $this->getAdsCount();
        $bookings = $this->getBookingsCount();
        $comments = $this->getCommentsCount();

        // compact rassemble un tableau avec le nom de la key = $key
        // par ex: 'users' = $users, 'ads' = $ads ...
        return compact('users', 'ads', 'bookings', 'comments');
    }
    public function getUsersCount()
    {
        return count($this->manager->getRepository(User::class)->findAll());
    }
    public function getAdscount()
    {
        return count($this->manager->getRepository(Ad::class)->findAll());
    }
    public function getBookingsCount()
    {
        return count($this->manager->getRepository(Booking::class)->findAll());
    }
    public function getCommentsCount()
    {
        return count($this->manager->getRepository(Comment::class)->findAll());
    }

    public function getAdsStats($direction)
    {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM APP\Entity\Comment c
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER by note ' . $direction
        )
            ->setMaxResults(5)
            ->getResult();
    }
}
