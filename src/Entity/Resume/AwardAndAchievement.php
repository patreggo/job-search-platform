<?php

namespace App\Entity\Resume;

use App\Repository\Resume\AwardAndAchievementRepository;
use App\Traits\TimestampTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AwardAndAchievementRepository::class)]
#[ORM\HasLifecycleCallbacks]
class AwardAndAchievement
{
    use TimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $award = null;

    #[Assert\File(
        maxSize: '10M',
        mimeTypes: [
            'image/jpg',
            'image/jpeg',
            'image/png',
        ]
    )]
    #[Assert\Image(
        maxWidth: 1920,
        maxHeight: 1080,
    )]
    private $awardFile = null;

    #[ORM\ManyToOne(targetEntity: Resume::class, cascade: ['persist', 'remove'], inversedBy: 'awardAndAchievement')]
    private ?Resume $resume = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAward(): ?string
    {
        return $this->award;
    }

    /**
     * @param string|null $award
     * @return $this
     */
    public function setAward(?string $award): self
    {
        $this->award = $award;

        return $this;
    }

    /**
     * @param null $awardFile
     */
    public function setAwardFile($awardFile = null): void
    {
        $this->awardFile = $awardFile;

        if (null !== $awardFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @return null
     */
    public function getAwardFile()
    {
        return $this->awardFile;
    }

    /**
     * @return Resume|null
     */
    public function getResume(): ?Resume
    {
        return $this->resume;
    }

    /**
     * @param Resume|null $resume
     * @return $this
     */
    public function setResume(?Resume $resume): static
    {
        $this->resume = $resume;

        return $this;
    }
}
