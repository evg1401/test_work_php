<?php

declare(strict_types=1);

namespace Fixtures;

use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class CategoryFixture extends AbstractFixture implements FixtureInterface
{
    /** @var list<array{title: string, slug: string, description: string}> */
    private const CATEGORIES = [
        [
            'title' => 'Комнатные растения',
            'slug' => 'indoor-plants',
            'description' => 'Растения для дома, квартиры и зимнего сада.',
        ],
        [
            'title' => 'Садовые растения',
            'slug' => 'garden-plants',
            'description' => 'Декоративные и цветущие растения для сада.',
        ],
        [
            'title' => 'Суккуленты и кактусы',
            'slug' => 'succulents-cacti',
            'description' => 'Засухоустойчивые растения и особенности ухода за ними.',
        ],
        [
            'title' => 'Лекарственные растения',
            'slug' => 'medicinal-plants',
            'description' => 'Растения, традиционно используемые в фитотерапии.',
        ],
        [
            'title' => 'Деревья и кустарники',
            'slug' => 'trees-shrubs',
            'description' => 'Древесные растения для сада и дома.',
        ],
        [
            'title' => 'Овощные культуры',
            'slug' => 'vegetable-crops',
            'description' => 'Выращивание овощей в открытом грунте, теплице и дома.',
        ],
        [
            'title' => 'Травы и пряности',
            'slug' => 'herbs-spices',
            'description' => 'Ароматные травы для сада, балкона и кухни.',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::CATEGORIES as $data) {
            $category = new Category(
                title: $data['title'],
                slug: $data['slug'],
                description: $data['description'],
            );

            $manager->persist($category);
            $this->addReference(self::reference($data['slug']), $category);
        }

        $manager->flush();
    }

    public static function reference(string $slug): string
    {
        return 'category.' . $slug;
    }
}
