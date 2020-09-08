<?php

namespace App\Controller;

use App\Service\GoogleAnalyticsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GoogleAnalyticsController extends AbstractController
{
    /**
     * @var GoogleAnalyticsService
     */
    private $googleAnalyticsService;


    public function __construct(GoogleAnalyticsService $googleAnalyticsService)
    {
        $this->googleAnalyticsService = $googleAnalyticsService;
    }

    /**
     * @Route("/", name="reportting")
     */
    public function index(): Response
    {
        if ($this->googleAnalyticsService->isLoggedIn() === true) {

            $results = $this->googleAnalyticsService->getChartResults('215737497', 'ga:pageviews,ga:users,ga:sessions');
            $rapport = $this->googleAnalyticsService->buildChartArray($results);
            //JSON Response
            return $this->json([
                'message' => 'Google Analytics reporting',
                'profile ID' => "215737497",
                'rapport' => $rapport
            ]);
        } else {
            $url = $this->googleAnalyticsService->getLoginUrl();
            return $this->redirect($url);
        }
    }

    /**
     * @Route("/login", name="google_login")
     */
    public function login(Request $request): Response
    {
        if ($request->query->has('code') === true) {
            $code = $request->query->get('code');
            $this->googleAnalyticsService->login($code);
            return $this->redirect('/');
        } else {
            return $this->json([
                'message' => 'Invalide request parameters',
            ]);
        }
    }
}
