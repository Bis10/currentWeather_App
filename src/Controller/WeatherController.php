<?php


namespace App\Controller;

use App\Service\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    private $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    #[Route('/api/weather', name: 'api_weather', methods: ['GET'])]
    public function getWeather(Request $request): JsonResponse
    {
        $city = $request->query->get('city');
        if (!$city) {
            return $this->json(['error' => 'City is required'], 400);
        }
        try {
            $weatherData = $this->weatherService->getWeather($city);
            return $this->json($weatherData);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Unable to fetch weather data'], 500);
        }
    }

    #[Route('/weather', name: 'weather_home', methods: ['GET'])]
    public function index()
    {
        return $this->render('weather/index.html.twig');
    }
}
