<?php

namespace App\Entity\Vacancy;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Attribute\Filterable;
use App\Entity\City;
use App\Entity\Company;
use App\Entity\Country;
use App\Entity\Resume\VacancyResponse;
use App\Entity\User;
use App\Enum\FilterType;
use App\Repository\Vacancy\VacancyRepository;
use App\Traits\IsActive;
use App\Traits\IsEnabledTrait;
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

#[ORM\Entity(repositoryClass: VacancyRepository::class)]
#[ApiResource(
    routePrefix: 'admin',
    normalizationContext: ['groups' => ['read']]
)]
#[ORM\HasLifecycleCallbacks]
class Vacancy
{
    use IsActive;
    use IsEnabledTrait;
    use TimestampTrait;
    use SlugTrait;
    use IsModeratedTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    #[Assert\NotNull]
    #[Filterable('name', FilterType::LIKE->value)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: VacancySpecializations::class)]
    #[Assert\Count(
        min: 1,
        max: 1,
        maxMessage: 'select_maximum_one_value'
    )]
    #[Filterable('specializations', FilterType::EXACT->value, 's.id', 'specializations.s')]
    private Collection $specializations;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\NotNull]
    private ?string $description = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isPartTimeJob = false;

    #[ORM\Column(type: 'boolean')]
    private bool $workingFromHome = false;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2, nullable: true)]
    #[Groups(['read'])]
    private ?string $incomeMin = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2, nullable: true)]
    #[Groups(['read'])]
    private ?string $incomeMax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $img = null;

    #[Assert\File(
        maxSize: '5000k',
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
    private $imgFile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotNull]
    private ?string $workAddress = null;

    #[ORM\Column]
    private bool $archived = false;

    #[ORM\ManyToOne(targetEntity: VacancyIncomePayment::class)]
    private ?VacancyIncomePayment $incomePayment = null;

    #[ORM\ManyToOne(targetEntity: Company::class, inversedBy: 'vacancies')]
    private ?Company $company = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $requirements = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $responsibilities = null;

    #[ORM\ManyToOne(targetEntity: Country::class, inversedBy: 'vacancies')]
    private ?Country $country = null;

    #[ORM\ManyToOne(targetEntity: City::class, inversedBy: 'vacancies')]
    private ?City $city = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DateTimeInterface $publicationDate = null;

    #[ORM\ManyToMany(targetEntity: VacancyCompanyIndustry::class)]
    private Collection $companyIndustry;

    #[ORM\ManyToMany(targetEntity: VacancyEmploymentType::class)]
    private Collection $employmentType;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachmentDocument = null;

    #[Assert\File(
        maxSize: '2192k',
        extensions: ['pdf'],
        extensionsMessage: 'file_extensions_message'
    )]
    private $attachmentDocumentFile = null;

    #[ORM\ManyToMany(targetEntity: City::class)]
    private Collection $searchCities;

    #[ORM\Column(nullable: true)]
    private ?bool $previouslyPublished = false;

    #[ORM\OneToMany(mappedBy: 'vacancy', targetEntity: VacancyResponse::class)]
    private Collection $vacancyResponses;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'vacancyFavorites')]
    private Collection $favoritesUsers;

    #[ORM\ManyToMany(targetEntity: VacancyWorkSchedule::class)]
    private Collection $workSchedule;

    #[ORM\ManyToOne(targetEntity: VacancyWorkExperience::class)]
    private ?VacancyWorkExperience $workExperience = null;


    public function __construct()
    {
        $this->specializations = new ArrayCollection();
        $this->companyIndustry = new ArrayCollection();
        $this->employmentType = new ArrayCollection();
        $this->searchCities = new ArrayCollection();
        $this->vacancyResponses = new ArrayCollection();
        $this->favoritesUsers = new ArrayCollection();
        $this->workSchedule = new ArrayCollection();
    }

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
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return $this
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, VacancySpecializations>
     */
    public function getSpecializations(): Collection
    {
        return $this->specializations;
    }

    /**
     * @param VacancySpecializations $specialization
     * @return $this
     */
    public function addSpecialization(VacancySpecializations $specialization): self
    {
        if (!$this->specializations->contains($specialization)) {
            $this->specializations->add($specialization);
        }

        return $this;
    }

    /**
     * @param VacancySpecializations $specialization
     * @return $this
     */
    public function removeSpecialization(VacancySpecializations $specialization): self
    {
        $this->specializations->removeElement($specialization);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }


    /**
     * @return bool
     */
    public function getIsPartTimeJob(): bool
    {
        return $this->isPartTimeJob;
    }

    /**
     * @param bool $isPartTimeJob
     */
    public function setIsPartTimeJob(bool $isPartTimeJob): void
    {
        $this->isPartTimeJob = $isPartTimeJob;
    }

    /**
     * @return bool
     */
    public function getWorkingFromHome(): bool
    {
        return $this->workingFromHome;
    }

    /**
     * @param bool $workingFromHome
     */
    public function setWorkingFromHome(bool $workingFromHome): void
    {
        $this->workingFromHome = $workingFromHome;
    }

    /**
     * @return string|null
     */
    public function getIncomeMin(): ?string
    {
        return $this->incomeMin;
    }

    /**
     * @param string|null $incomeMin
     * @return $this
     */
    public function setIncomeMin(?string $incomeMin): self
    {
        $this->incomeMin = $incomeMin;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getIncomeMax(): ?string
    {
        return $this->incomeMax;
    }

    /**
     * @param string|null $incomeMax
     * @return $this
     */
    public function setIncomeMax(?string $incomeMax): self
    {
        $this->incomeMax = $incomeMax;

        return $this;
    }


    /**
     * @return string|null
     */
    public function getImg(): ?string
    {
        return $this->img;
    }

    /**
     * @param string|null $img
     *
     * @return $this
     */
    public function setImg(?string $img): self
    {
        $this->img = $img;

        return $this;
    }

    /**
     * @param null $imgFile
     */
    public function setImgFile($imgFile = null): void
    {
        $this->imgFile = $imgFile;

        if (null !== $imgFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @return null
     */
    public function getImgFile()
    {
        return $this->imgFile;
    }

    /**
     * @return string|null
     */
    public function getWorkAddress(): ?string
    {
        return $this->workAddress;
    }

    /**
     * @param string|null $workAddress
     * @return $this
     */
    public function setWorkAddress(?string $workAddress): self
    {
        $this->workAddress = $workAddress;

        return $this;
    }

    /**
     * @return bool
     */
    public function getArchived(): bool
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived(bool $archived): void
    {
        $this->archived = $archived;
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
     * @return Company|null
     */
    public function getCompany(): ?Company
    {
        return $this->company;
    }

    /**
     * @param Company|null $company
     * @return $this
     */
    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRequirements(): ?string
    {
        return $this->requirements;
    }

    /**
     * @param string|null $requirements
     * @return $this
     */
    public function setRequirements(?string $requirements): self
    {
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getResponsibilities(): ?string
    {
        return $this->responsibilities;
    }

    /**
     * @param string|null $responsibilities
     * @return $this
     */
    public function setResponsibilities(?string $responsibilities): self
    {
        $this->responsibilities = $responsibilities;

        return $this;
    }

    /**
     * @return Country|null
     */
    public function getCountry(): ?Country
    {
        return $this->country;
    }

    /**
     * @param Country|null $country
     * @return $this
     */
    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     * @return $this
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPublicationDate(): ?DateTimeInterface
    {
        return $this->publicationDate;
    }

    /**
     * @param DateTimeInterface|null $publicationDate
     * @return $this
     */
    public function setPublicationDate(?DateTimeInterface $publicationDate): self
    {
        $this->publicationDate = $publicationDate;

        return $this;
    }

    /**
     * @return Collection<int, VacancyCompanyIndustry>
     */
    public function getCompanyIndustry(): Collection
    {
        return $this->companyIndustry;
    }

    /**
     * @param VacancyCompanyIndustry $companyIndustry
     * @return $this
     */
    public function addCompanyIndustry(VacancyCompanyIndustry $companyIndustry): self
    {
        if (!$this->companyIndustry->contains($companyIndustry)) {
            $this->companyIndustry->add($companyIndustry);
        }

        return $this;
    }

    /**
     * @param VacancyCompanyIndustry $companyIndustry
     * @return $this
     */
    public function removeCompanyIndustry(VacancyCompanyIndustry $companyIndustry): self
    {
        $this->companyIndustry->removeElement($companyIndustry);

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
    public function addEmploymentType(VacancyEmploymentType $employmentType): self
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
    public function removeEmploymentType(VacancyEmploymentType $employmentType): self
    {
        $this->employmentType->removeElement($employmentType);

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAttachmentDocument(): ?string
    {
        return $this->attachmentDocument;
    }

    /**
     * @param string|null $attachmentDocument
     * @return $this
     */
    public function setAttachmentDocument(?string $attachmentDocument): self
    {
        $this->attachmentDocument = $attachmentDocument;

        return $this;
    }

    /**
     * @param null $attachmentDocumentFile
     */
    public function setAttachmentDocumentFile($attachmentDocumentFile = null): void
    {
        $this->attachmentDocumentFile = $attachmentDocumentFile;

        if (null !== $attachmentDocumentFile) {
            $this->updatedAt = new \DateTime();
        }
    }

    /**
     * @return null
     */
    public function getAttachmentDocumentFile()
    {
        return $this->attachmentDocumentFile;
    }


    /**
     * @return Collection<int, City>
     */
    public function getSearchCities(): Collection
    {
        return $this->searchCities;
    }

    /**
     * @param City $searchCity
     * @return $this
     */
    public function addSearchCity(City $searchCity): self
    {
        if (!$this->searchCities->contains($searchCity)) {
            $this->searchCities->add($searchCity);
        }

        return $this;
    }

    /**
     * @param City $searchCity
     * @return $this
     */
    public function removeSearchCity(City $searchCity): self
    {
        $this->searchCities->removeElement($searchCity);

        return $this;
    }


    /**
     * @return bool|null
     */
    public function getPreviouslyPublished(): ?bool
    {
        return $this->previouslyPublished;
    }

    /**
     * @param bool|null $previouslyPublished
     */
    public function setPreviouslyPublished(?bool $previouslyPublished): void
    {
        $this->previouslyPublished = $previouslyPublished;
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
            $vacancyResponse->setVacancy($this);
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
            if ($vacancyResponse->getVacancy() === $this) {
                $vacancyResponse->setVacancy(null);
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

    public function addFavoritesUser(User $favoritesUser): static
    {
        if (!$this->favoritesUsers->contains($favoritesUser)) {
            $this->favoritesUsers->add($favoritesUser);
            $favoritesUser->addVacancyFavorite($this);
        }

        return $this;
    }

    public function removeFavoritesUser(User $favoritesUser): static
    {
        if ($this->favoritesUsers->removeElement($favoritesUser)) {
            $favoritesUser->removeVacancyFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, VacancyWorkSchedule>
     */
    public function getWorkSchedule(): Collection
    {
        return $this->workSchedule;
    }

    public function addWorkSchedule(VacancyWorkSchedule $workSchedule): static
    {
        if (!$this->workSchedule->contains($workSchedule)) {
            $this->workSchedule->add($workSchedule);
        }

        return $this;
    }

    public function removeWorkSchedule(VacancyWorkSchedule $workSchedule): static
    {
        $this->workSchedule->removeElement($workSchedule);

        return $this;
    }

    public function getWorkExperience(): ?VacancyWorkExperience
    {
        return $this->workExperience;
    }

    public function setWorkExperience(?VacancyWorkExperience $workExperience): Vacancy
    {
        $this->workExperience = $workExperience;

        return $this;
    }
}
