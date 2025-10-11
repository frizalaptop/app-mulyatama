<?php
namespace App\Services;

use Throwable;

class ProfilService 
{
  /**
     * Mengembalikan data title
     * @return array{title: string}
     */
    public function getProfilViewData()
    {
        try {
            return ['title' => 'Profil'];
        } catch (Throwable $e) {
            throw $e;
        }
    }
}