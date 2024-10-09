<?php

namespace msng\ImageFetcher;

class SafeSearchAnnotation
{
    const ADULT = 'adult';
    const SPOOF = 'spoof';
    const MEDICAL = 'medical';
    const VIOLENCE = 'violence';
    const RACY = 'racy';

    private array $likelihoods = [
        self::ADULT => Likelihood::UNKNOWN,
        self::SPOOF => Likelihood::UNKNOWN,
        self::MEDICAL => Likelihood::UNKNOWN,
        self::VIOLENCE => Likelihood::UNKNOWN,
        self::RACY => Likelihood::UNKNOWN
    ];

    public function __construct(array $likelihoods = null)
    {
        $categories = [
            self::ADULT,
            self::SPOOF,
            self::MEDICAL,
            self::VIOLENCE,
            self::RACY,
        ];

        foreach ($categories as $category) {
            if (isset($likelihoods[$category])) {
                $this->likelihoods[$category] = $likelihoods[$category];
            }
        }
    }

    /**
     * @param int $adult
     * @return SafeSearchAnnotation
     */
    public function setAdult(int $adult): SafeSearchAnnotation
    {
        $this->likelihoods[self::ADULT] = $adult;

        return $this;
    }

    public function getAdult(): int
    {
        return $this->likelihoods[self::ADULT];
    }

    public function setSpoof(int $spoof): SafeSearchAnnotation
    {
        $this->likelihoods[self::SPOOF] = $spoof;

        return $this;
    }

    public function getSpoof(): int
    {
        return $this->likelihoods[self::SPOOF];
    }

    public function setMedical(int $medical): SafeSearchAnnotation
    {
        $this->likelihoods[self::MEDICAL] = $medical;

        return $this;
    }

    public function getMedical(): int
    {
        return $this->likelihoods[self::MEDICAL];
    }

    public function setViolence(int $violence): SafeSearchAnnotation
    {
        $this->likelihoods[self::VIOLENCE] = $violence;

        return $this;
    }

    public function getViolence(): int
    {
        return $this->likelihoods[self::VIOLENCE];
    }

    public function setRacy(int $racy): SafeSearchAnnotation
    {
        $this->likelihoods[self::RACY] = $racy;

        return $this;
    }

    public function getRacy(): int
    {
        return $this->likelihoods[self::RACY];
    }

    public function toArray(): array
    {
        return $this->likelihoods;
    }

    public function toJson(): string
    {
        return json_encode($this->likelihoods);
    }
}
