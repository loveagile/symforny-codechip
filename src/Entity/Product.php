<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Asserts;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\Table(name="products")
 */
class Product
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Asserts\NotBlank(message="Este valor não deve ficar em branco.")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Asserts\NotBlank(message="Este valor não deve ficar em branco.")
     * @Asserts\Length(min=30, minMessage="Descrição deve ter pelos menos 30 caracteres!")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Asserts\NotBlank(message="Este valor não deve ficar em branco.")
     */
    private $body;

    /**
     * @ORM\Column(type="integer")
     * @Asserts\NotBlank(message="Este valor não deve ficar em branco.")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     * @Gedmo\Slug(fields={"name"})
     */
    private $slug;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="product")
     * @Asserts\NotBlank()
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity=ProductPhoto::class, mappedBy="product", orphanRemoval=true, cascade={"persist"})
     */
    private $productPhotos;

    public function __construct()
    {
        $this->category = new ArrayCollection();
        $this->productPhotos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function getFloatPrice(): ?float
    {
        $price= $this->getPrice();
        return $price / 100;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }

    /**
     * @return Collection|ProductPhoto[]
     */
    public function getProductPhotos(): Collection
    {
        return $this->productPhotos;
    }

    public function addManyProductPhoto(array $productPhotoEntities)
    {
        foreach ($productPhotoEntities as $entity) {
            $this->addProductPhoto($entity);
        }
        return $this;
    }

    public function addProductPhoto(ProductPhoto $productPhoto): self
    {
        if (!$this->productPhotos->contains($productPhoto)) {
            $this->productPhotos[] = $productPhoto;
            $productPhoto->setProduct($this);
        }

        return $this;
    }

    public function removeProductPhoto(ProductPhoto $productPhoto): self
    {
        if ($this->productPhotos->removeElement($productPhoto)) {
            // set the owning side to null (unless already changed)
            if ($productPhoto->getProduct() === $this) {
                $productPhoto->setProduct(null);
            }
        }

        return $this;
    }
}
