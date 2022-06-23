<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']]
)]
#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', )]
    #[Groups('read')]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    private $title;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['read', 'write'])]
    private $content;

    #[ORM\Column(type: 'datetime')]
    #[Groups('read')]
    private $date;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: 'id')]
    #[MaxDepth(1)]
    #[Groups('read')]
    private $author;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'comments')]
    #[Groups(['read', 'write'])]
    private $Parent_Post;

    #[ORM\OneToMany(mappedBy: 'Parent_Post', targetEntity: self::class)]
    private $comments;

    public function __construct()
    {
        $this->date = new \DateTime('now');
        $this->comments = new ArrayCollection();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getParentPost(): ?self
    {
        return $this->Parent_Post;
    }

    public function setParentPost(?self $Parent_Post): self
    {
        $this->Parent_Post = $Parent_Post;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(self $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setParentPost($this);
        }

        return $this;
    }

    public function removeComment(self $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getParentPost() === $this) {
                $comment->setParentPost(null);
            }
        }

        return $this;
    }



}
