<?php

namespace App\Entity\Resume;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\Vacancy\VacancyCompanyIndustry;
use App\Entity\Vacancy\VacancyEmploymentType;
use App\Entity\Vacancy\VacancyIncomePayment;
use App\Entity\Vacancy\VacancySpecializations;
use App\Entity\Vacancy\VacancyWorkSchedule;
use App\Repository\Resume\ResumeRepository;
use App\Traits\IsActive;
use App\Traits\IsModeratedTrait;
use App\Traits\SlugTrait;
use App\Traits\TimestampTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ResumeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(filterClass: BooleanFilter::class, properties: ['isModerated'])]
class Resume
{
    use TimestampTrait;
    use IsActive;
    use IsModeratedTrait;
    use SlugTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('read')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: VacancySpecializations::class)]
    #[Assert\NotNull]
    private ?VacancySpecializations $specialization = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $desiredSalary = null;

    #[ORM\ManyToOne(targetEntity: VacancyIncomePayment::class)]
    private ?VacancyIncomePayment $incomePayment = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    #[Groups('read')]
    private ?string $firstName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    #[Groups('read')]
    private ?string $lastName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $birthDate = null;

    #[ORM\ManyToOne(targetEntity: City::class)]
    #[Assert\NotNull]
    private ?City $residenceCity = null;

    #[ORM\ManyToMany(targetEntity: Country::class)]
    #[ORM\JoinTable(name: "resume_citizenship_country")]
    #[Assert\Count(min: 1)]
    private Collection $citizenship;

    #[ORM\OneToMany(targetEntity: WorkPlace::class, mappedBy: 'resume', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $workPlace;

    #[ORM\OneToMany(targetEntity: Education::class, mappedBy: 'resume', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $education;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $additionalInformation = null;

    #[ORM\ManyToMany(targetEntity: City::class)]
    #[Assert\NotNull]
    private Collection $relocationCity;

    #[ORM\ManyToMany(targetEntity: VacancyEmploymentType::class)]
    #[Assert\Count(min: 1)]
    private Collection $employmentType;

    #[ORM\ManyToOne(targetEntity: VacancyWorkSchedule::class)]
    #[Assert\NotNull]
    private ?VacancyWorkSchedule $workSchedule = null;

    #[ORM\Column(nullable: true)]
    private ?bool $havePersonalCar = false;

    #[ORM\ManyToMany(targetEntity: Country::class)]
    #[ORM\JoinTable(name: "resume_work_permit_country")]
    private Collection $workPermitCountry;

    #[ORM\OneToMany(targetEntity: AwardAndAchievement::class, mappedBy: 'resume', cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Assert\Count(max: 10)]
    #[Assert\Valid]
    private Collection $awardAndAchievement;

    #[ORM\ManyToOne(targetEntity: Gender::class)]
    private ?Gender $gender = null;

    #[ORM\ManyToOne(targetEntity: VacancyCompanyIndustry::class)]
    private ?VacancyCompanyIndustry $jobIndustry = null;

    #[ORM\ManyToOne(inversedBy: 'resumes')]
    #[Assert\NotNull]
    #[Groups('read')]
    private ?User $user = null;

    #[ORM\OneToMany(targetEntity: VacancyResponse::class, mappedBy: 'resume')]
    private Collection $vacancyResponses;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'resumeFavorites')]
    private Collection $favoritesUsers;

    public function __construct()
    {
        $this->citizenship = new ArrayCollection();
        $this->relocationCity = new ArrayCollection();
        $this->employmentType = new ArrayCollection();
        $this->workPermitCountry = new ArrayCollection();
        $this->awardAndAchievement = new ArrayCollection();
        $this->education = new ArrayCollection();
        $this->workPlace = new ArrayCollection();
        $this->vacancyResponses = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return VacancySpecializations|null
     */
    public function getSpecialization(): ?VacancySpecializations
    {
        return $this->specialization;
    }

    /**
     * @param VacancySpecializations|null $specialization
     * @return $this
     */
    public function setSpecialization(?VacancySpecializations $specialization): static
    {
        $this->specialization = $specialization;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDesiredSalary(): ?string
    {
        return $this->desiredSalary;
    }

    /**
     * @param string|null $desiredSalary
     * @return $this
     */
    public function setDesiredSalary(?string $desiredSalary): static
    {
        $this->desiredSalary = $desiredSalary;

        return $this;
    }

    /**
     * @return VacancyIncomePayment|null
     */
    public function getIncomePayment(): ?VacancyIncomePayment
    {
        return $this->incomePayment;
    }

    /**
     * @param VacancyIncomePayment|null $incomePayment
     * @return $this
     */
    public function setIncomePayment(?VacancyIncomePayment $incomePayment): self
    {
        $this->incomePayment = $incomePayment;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     * @return $this
     */
    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getBirthDate(): ?DateTimeInterface
    {
        return $this->birthDate;
    }

    /**
     * @param DateTimeInterface|null $birthDate
     * @return $this
     */
    public function setBirthDate(?DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return City|null
     */
    public function getResidenceCity(): ?City
    {
        return $this->residenceCity;
    }

    /**
     * @return string|null
     */
    public function getResidenceCityName(): ?string
    {
        return $this->residenceCity?->getName();
    }

    /**
     * @param City|null $residenceCity
     * @return $this
     */
    public function setResidenceCity(?City $residenceCity): static
    {
        $this->residenceCity = $residenceCity;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCitizenship(): Collection
    {
        return $this->citizenship;
    }

    /**
     * @param Country $citizenship
     * @return $this
     */
    public function addCitizenship(Country $citizenship): static
    {
        if (!$this->citizenship->contains($citizenship)) {
            $this->citizenship->add($citizenship);
        }

        return $this;
    }

    /**
     * @param Country $citizenship
     * @return $this
     */
    public function removeCitizenship(Country $citizenship): static
    {
        $this->citizenship->removeElement($citizenship);

        return $this;
    }

    /**
     * @return Collection<int, WorkPlace>
     */
    public function getWorkPlace(): Collection
    {
        return $this->workPlace;
    }

    /**
     * @param WorkPlace $workPlace
     * @return $this
     */
    public function addWorkPlace(WorkPlace $workPlace): static
    {
        if (!$this->workPlace->contains($workPlace)) {
            $this->workPlace->add($workPlace);
            $workPlace->setResume($this);
        }

        return $this;
    }

    /**
     * @param WorkPlace $workPlace
     * @return $this
     */
    public function removeWorkPlace(WorkPlace $workPlace): static
    {
        if ($this->workPlace->removeElement($workPlace)) {
            // set the owning side to null (unless already changed)
            if ($workPlace->getResume() === $this) {
                $workPlace->setResume(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Education>
     */
    public function getEducation(): Collection
    {
        return $this->education;
    }

    /**
     * @param Education $education
     * @return $this
     */
    public function addEducation(Education $education): static
    {
        if (!$this->education->contains($education)) {
            $this->education->add($education);
            $education->setResume($this);
        }

        return $this;
    }

    /**
     * @param Education $education
     * @return $this
     */
    public function removeEducation(Education $education): static
    {
        if ($this->education->removeElement($education)) {
            // set the owning side to null (unless already changed)
            if ($education->getResume() === $this) {
                $education->setResume(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    /**
     * @param string|null $additionalInformation
     * @return $this
     */
    public function setAdditionalInformation(?string $additionalInformation): static
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getRelocationCity(): Collection
    {
        return $this->relocationCity;
    }

    /**
     * @param City $relocationCity
     * @return $this
     */
    public function addRelocationCity(City $relocationCity): static
    {
        if (!$this->relocationCity->contains($relocationCity)) {
            $this->relocationCity->add($relocationCity);
        }

        return $this;
    }

    /**
     * @param City $relocationCity
     * @return $this
     */
    public function removeRelocationCity(City $relocationCity): static
    {
        $this->relocationCity->removeElement($relocationCity);

        return $this;
    }

    /**
     * @return Collection<int, VacancyEmploymentType>
     */
    public function getEmploymentType(): Collection
    {
        return $this->employmentType;
    }

    /**
     * @param VacancyEmploymentType $employmentType
     * @return $this
     */
    public function addEmploymentType(VacancyEmploymentType $employmentType): static
    {
        if (!$this->employmentType->contains($employmentType)) {
            $this->employmentType->add($employmentType);
        }

        return $this;
    }

    /**
     * @param VacancyEmploymentType $employmentType
     * @return $this
     */
    public function removeEmploymentType(VacancyEmploymentType $employmentType): static
    {
        $this->employmentType->removeElement($employmentType);

        return $this;
    }

    /**
     * @return VacancyWorkSchedule|null
     */
    public function getWorkSchedule(): ?VacancyWorkSchedule
    {
        return $this->workSchedule;
    }

    /**
     * @param VacancyWorkSchedule|null $workSchedule
     * @return $this
     */
    public function setWorkSchedule(?VacancyWorkSchedule $workSchedule): self
    {
        $this->workSchedule = $workSchedule;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function isHavePersonalCar(): ?bool
    {
        return $this->havePersonalCar;
    }

    /**
     * @param bool|null $havePersonalCar
     * @return $this
     */
    public function setHavePersonalCar(?bool $havePersonalCar): static
    {
        $this->havePersonalCar = $havePersonalCar;

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getWorkPermitCountry(): Collection
    {
        return $this->workPermitCountry;
    }

    /**
     * @param Country $workPermitCountry
     * @return $this
     */
    public function addWorkPermitCountry(Country $workPermitCountry): static
    {
        if (!$this->workPermitCountry->contains($workPermitCountry)) {
            $this->workPermitCountry->add($workPermitCountry);
        }

        return $this;
    }

    /**
     * @param Country $workPermitCountry
     * @return $this
     */
    public function removeWorkPermitCountry(Country $workPermitCountry): static
    {
        $this->workPermitCountry->removeElement($workPermitCountry);

        return $this;
    }

    /**
     * @return Collection<int, AwardAndAchievement>
     */
    public function getAwardAndAchievement(): Collection
    {
        return $this->awardAndAchievement;
    }

    /**
     * @param AwardAndAchievement $awardAndAchievement
     * @return $this
     */
    public function addAwardAndAchievement(AwardAndAchievement $awardAndAchievement): static
    {
        if (!$this->awardAndAchievement->contains($awardAndAchievement)) {
            $this->awardAndAchievement->add($awardAndAchievement);
            $awardAndAchievement->setResume($this);
        }

        return $this;
    }

    /**
     * @param AwardAndAchievement $awardAndAchievement
     * @return $this
     */
    public function removeAwardAndAchievement(AwardAndAchievement $awardAndAchievement): static
    {
        if ($this->awardAndAchievement->removeElement($awardAndAchievement)) {
            // set the owning side to null (unless already changed)
            if ($awardAndAchievement->getResume() === $this) {
                $awardAndAchievement->setResume(null);
                $awardAndAchievement->setAward(null);
            }
        }

        return $this;
    }

    /**
     * @return Gender|null
     */
    public function getGender(): ?Gender
    {
        return $this->gender;
    }

    /**
     * @param Gender|null $gender
     * @return $this
     */
    public function setGender(?Gender $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return VacancyCompanyIndustry|null
     */
    public function getJobIndustry(): ?VacancyCompanyIndustry
    {
        return $this->jobIndustry;
    }

    /**
     * @param VacancyCompanyIndustry|null $jobIndustry
     * @return $this
     */
    public function setJobIndustry(?VacancyCompanyIndustry $jobIndustry): static
    {
        $this->jobIndustry = $jobIndustry;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @return string|null
     */
    public function getUserEmail(): ?string
    {
        return $this->user->getEmail();
    }

    /**
     * @param User|null $user
     * @return $this
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, VacancyResponse>
     */
    public function getVacancyResponses(): Collection
    {
        return $this->vacancyResponses;
    }

    /**
     * @param VacancyResponse $vacancyResponse
     * @return $this
     */
    public function addVacancyResponse(VacancyResponse $vacancyResponse): static
    {
        if (!$this->vacancyResponses->contains($vacancyResponse)) {
            $this->vacancyResponses->add($vacancyResponse);
            $vacancyResponse->setResume($this);
        }

        return $this;
    }

    /**
     * @param VacancyResponse $vacancyResponse
     * @return $this
     */
    public function removeVacancyResponse(VacancyResponse $vacancyResponse): static
    {
        if ($this->vacancyResponses->removeElement($vacancyResponse)) {
            // set the owning side to null (unless already changed)
            if ($vacancyResponse->getResume() === $this) {
                $vacancyResponse->setResume(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoritesUsers(): Collection
    {
        return $this->favoritesUsers;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function addFavoritesUser(User $user): self
    {
        if (!$this->favoritesUsers->contains($user)) {
            $this->favoritesUsers->add($user);
            $user->addResumeFavorite($this);
        }

        return $this;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function removeFavoritesUser(User $user): self
    {
        if ($this->favoritesUsers->removeElement($user)) {
            $user->removeResumeFavorite($this);
        }

        return $this;
    }
}
