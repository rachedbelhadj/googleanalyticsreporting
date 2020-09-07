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
            $accounts = $this
                ->googleAnalyticsService
                ->analytics
                ->management_accounts
                ->listManagementAccounts();

            //JSON Response
            return $this->json([
                'message' => 'Google Analytics reporting',
                'accounts number' => count($accounts->getItems())
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
