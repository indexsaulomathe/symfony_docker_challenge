<?php

namespace App\Service;

use App\Repository\CitizenRepository;

class NisGeneratorService
{
    private CitizenRepository $citizenRepository;

    public function __construct(CitizenRepository $citizenRepository)
    {
        $this->citizenRepository = $citizenRepository;
    }

    public function generateUniqueNIS(): string
    {
        do {
            $nis = random_int(10000000000, 99999999999);
            $existingCitizen = $this->citizenRepository->findOneBy(['nis' => $nis]);
        } while ($existingCitizen !== null);

        return (string) $nis;
    }
}
