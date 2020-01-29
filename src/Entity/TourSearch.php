<?php


namespace App\Entity;


class TourSearch
{
    /**
     * @var string|null
     */
    private $city;

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

}
