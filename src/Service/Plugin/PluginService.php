<?php

namespace App\Service\Plugin;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Entity\Rating;
use App\Entity\Credentials;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PluginService
{
    private $entityManager;
    private $container;

    public function __construct(EntityManagerInterface $entityManager, ContainerInterface $container)
    {
        $this->entityManager = $entityManager;
        $this->container = $container;
    }

    /**
     * ! you should use the first parameter as object not id when you call this function from the controller
     * ? public function saveCredentials(object $user, object $plugin, array $credentials_data)
     * 
     * @param object $user
     * @param object $plugin
     * @param array $credentials_data
     * @return void
     */ 

    public function saveCredentials($user, object $plugin, array $credentials_data)
    {
        $credentials = new Credentials();

        // ! you should send user as object not id from the controller
        // ! you should delete this line when you implement the feature in SNAZZY CODE
        $user = $this->entityManager->getRepository(User::class)->find($user);
        // ! -------------------------------------------------------------------

        try {
            $credentials->setUser($user);
            $credentials->setPlugin($plugin);

            $fields_array = $plugin->getCredentialsFormFields() ?? [];
            $credentials_array = [];

            foreach ($fields_array as $field) {
                $credentials_array[$field] = $credentials_data[$field];
            }

            $credentials_object = (object) $credentials_array;

            $credentials->setCredentials($credentials_object);

            // ? Any plugin that has credentials should have a service that implements the verifyCredentials method
            if (!$this->verifyCredentials($plugin, $credentials_object)) 
                throw new \Exception('Credentials Verification Failed');
            
            // ? install the plugin to the user
            $plugin->addUser($user);


            $this->entityManager->persist($credentials);
            $this->entityManager->flush();

        } catch (\Exception $e) {
            throw new \Exception('Credentials Saving Failed');
        }
    }

    /**
     * ! you should use the first parameter as object not id when you call this function from the controller
     * ? public function ratePlugin(object $user, object $plugin, int $rating, string $review = null)
     * 
     * @param object $user
     * @param object $plugin
     * @param int $rating
     * @param string $review
     * @return void
     */

    public function ratePlugin($user, $plugin, $rating, $review = null)
    {
        // ! you should send user as object not id from the controller
        // ! you should delete this line when you implement the feature in SNAZZY CODE
        $user = $this->entityManager->getRepository(User::class)->find($user);
        // ! -------------------------------------------------------------------

        try {
            $Rating = new Rating();
            $Rating->setUser($user);
            $Rating->setPlugin($plugin);
            $Rating->setRating(intval($rating));
            $Rating->setComment($review);

            $this->entityManager->persist($Rating);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Rating Failed');
        }
    }


    /**
     * ? this function will return the rating stats for the plugin
     * 
     * @param object $plugin
     * @return array
     * 
     */

    public function gatRatingStats($plugin) : array
    {
        $ratings = $plugin->getRatings();

        $ratings_count = count($ratings);

        $ratings_sum = 0;

        foreach ($ratings as $rating) {
            $ratings_sum += $rating->getRating();
        }

        // ? calculate the average rating
        $average_rating = $ratings_count > 0 ? $ratings_sum / $ratings_count : 0;
        $average_rating = number_format($average_rating, 2);

        // ? calculate the percentage of each rating

        // ? calculate the percentage of one star rating
        $one_star_count = 0;

        foreach ($ratings as $rating) {
            if ($rating->getRating() == 1) {
                $one_star_count++;
            }
        }

        $one_star_percentage = $ratings_count > 0 ? ($one_star_count / $ratings_count) * 100 : 0;
        $one_star_percentage = number_format($one_star_percentage, 2);


        // ? calculate the percentage of two star rating
        $two_star_count = 0;

        foreach ($ratings as $rating) {
            if ($rating->getRating() == 2) {
                $two_star_count++;
            }
        }

        $two_star_percentage = $ratings_count > 0 ? ($two_star_count / $ratings_count) * 100 : 0;
        $two_star_percentage = number_format($two_star_percentage, 2);


        // ? calculate the percentage of three star rating
        $three_star_count = 0;

        foreach ($ratings as $rating) {
            if ($rating->getRating() == 3) {
                $three_star_count++;
            }
        }

        $three_star_percentage = $ratings_count > 0 ? ($three_star_count / $ratings_count) * 100 : 0;
        $three_star_percentage = number_format($three_star_percentage, 2);


        // ? calculate the percentage of four star rating
        $four_star_count = 0;

        foreach ($ratings as $rating) {
            if ($rating->getRating() == 4) {
                $four_star_count++;
            }
        }

        $four_star_percentage = $ratings_count > 0 ? ($four_star_count / $ratings_count) * 100 : 0;
        $four_star_percentage = number_format($four_star_percentage, 2);


        // ? calculate the percentage of five star rating
        $five_star_count = 0;

        foreach ($ratings as $rating) {
            if ($rating->getRating() == 5) {
                $five_star_count++;
            }
        }

        $five_star_percentage = $ratings_count > 0 ? ($five_star_count / $ratings_count) * 100 : 0;
        $five_star_percentage = number_format($five_star_percentage, 2);


        return [
            'ratings_count'  => $ratings_count,
            'average_rating' => $average_rating,
            'one_star'       => [
                'count'      => $one_star_count,
                'percentage' => $one_star_percentage,
            ],
            'two_star' => [
                'count'      => $two_star_count,
                'percentage' => $two_star_percentage,
            ],
            'three_star' => [
                'count'      => $three_star_count,
                'percentage' => $three_star_percentage,
            ],
            'four_star' => [
                'count'      => $four_star_count,
                'percentage' => $four_star_percentage,
            ],
            'five_star' => [
                'count'      => $five_star_count,
                'percentage' => $five_star_percentage,
            ],
        ];
    }

    /**
     * ? this function will verify the credentials of the plugin
     * 
     * @param object $plugin
     * @param object $credentials_object
     * @return bool
     * 
     */

    public function verifyCredentials($plugin, $credentials_object)
    {

        /** 
         * ! you should implement the plugin service alias in Services.yaml using its name by the following steps:
         * ! use snake_case
         * ! Concatenate the plugin name with _service
         * ! replace the spaces with _
         * ? Example:
         * ? if the plugin name is hubspot
         * ? the service alias should be hubspot_service
         * ? if the plugin name is google analytics
         * ? the service alias should be google_analytics_service
         */

        $serviceAlias = strtolower(str_replace(' ', '_', $plugin->getName())) . '_service';
        $serviceAlias = 'test_service';

        if (!$this->container->has($serviceAlias)) {
            throw new \InvalidArgumentException(sprintf('Service alias "%s" does not exist.', $serviceAlias));
        }

        $ServiceInstance = $this->container->get($serviceAlias);

        if (!method_exists($ServiceInstance, 'verifyCredentials')) {
            throw new \InvalidArgumentException(sprintf('Service "%s" does not have a verifyCredentials method.', $serviceName));
        }


        return $ServiceInstance->verifyCredentials($credentials_object);
    }


    /**
     * ? this function will uninstall the plugin from the user
     * ! you should use the first parameter as object not id when you call this function from the controller
     * ? public function uninstallPlugin(object $user, object $plugin)
     * 
     * @param object $plugin
     * @param object $user
     * @return void
     * 
     */

    public function uninstallPlugin($user, object $plugin)
    {
        // ! you should send user as object not id from the controller
        // ! you should delete this line when you implement the feature in SNAZZY CODE
        $user = $this->entityManager->getRepository(User::class)->find($user);
        // ! -------------------------------------------------------------------

        try {
            $plugin->removeUser($user);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception('Uninstall Plugin Failed');
        }
    }


}
