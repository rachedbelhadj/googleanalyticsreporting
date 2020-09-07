<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GoogleAnalyticsController extends AbstractController
{
    /**
     * @Route("/google/analytics", name="google_analytics")
     */
    public function index()
    {
        //JSON Response
        return $this->json([
            'message' => 'Google Analytics reporting',
        ]);
    }
}
