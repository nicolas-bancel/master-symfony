<?php

namespace App\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;

class TagsArrayToStringTransformer implements DataTransformerInterface
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function transform($tags): string
    {
        return implode(', ', $tags);
    }

    public function reverseTransform($string): array
    {
        $names = array_filter(array_unique(array_map('trim', explode(', ', $string))));
        $tags = [];
        $tags = $this->tagRepository->findBy(['Name' => $names]);
        $newNames = array_diff($names, $tags);

        foreach ($newNames as $name)
        {
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }

        return $tags;
    }
}