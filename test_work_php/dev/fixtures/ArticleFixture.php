<?php

declare(strict_types=1);

namespace Fixtures;

use App\Article\Infrastructure\Persistence\Doctrine\Entity\Article;
use App\Category\Infrastructure\Persistence\Doctrine\Entity\Category;
use DateTimeImmutable;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class ArticleFixture extends AbstractFixture implements DependentFixtureInterface, FixtureInterface
{
    /**
     * @var list<array{
     *     title: string,
     *     slug: string,
     *     image: ?string,
     *     description: string,
     *     text: string,
     *     views: int,
     *     categories: non-empty-list<string>
     * }>
     */
    private const ARTICLES = [
        ['title' => 'Алоэ вера: уход и полезные свойства', 'slug' => 'aloe-vera-care', 'image' => 'resources/images/aloe-vera-care.png', 'description' => 'Как вырастить здоровое алоэ в домашних условиях.', 'text' => 'Алоэ вера нуждается в ярком рассеянном свете, редком поливе и хорошо дренированном грунте.', 'views' => 480, 'categories' => ['indoor-plants', 'succulents-cacti', 'medicinal-plants', 'trees-shrubs']],
        ['title' => 'Розмарин в саду', 'slug' => 'rosemary-growing', 'image' => 'resources/images/rosemary-growing.png', 'description' => 'Посадка и формирование ароматного розмарина.', 'text' => 'Розмарин любит солнце, легкую почву и умеренный полив. Регулярная обрезка делает куст густым.', 'views' => 360, 'categories' => ['garden-plants', 'herbs-spices', 'trees-shrubs', 'vegetable-crops']],
        ['title' => 'Лимонное дерево дома', 'slug' => 'indoor-lemon-tree', 'image' => 'resources/images/indoor-lemon-tree.png', 'description' => 'Условия для цветения и плодоношения комнатного лимона.', 'text' => 'Лимону нужны долгий световой день, стабильная температура и регулярные подкормки в период роста.', 'views' => 520, 'categories' => ['indoor-plants', 'trees-shrubs']],
        ['title' => 'Шалфей: ароматная аптека в саду', 'slug' => 'medicinal-sage', 'image' => 'resources/images/medicinal-sage.png', 'description' => 'Выращивание и заготовка лекарственного шалфея.', 'text' => 'Шалфей предпочитает сухие солнечные участки. Листья собирают до цветения и сушат в тени.', 'views' => 280, 'categories' => ['medicinal-plants', 'herbs-spices']],
        ['title' => 'Мята без лишнего разрастания', 'slug' => 'garden-mint', 'image' => 'resources/images/garden-mint.png', 'description' => 'Как контролировать мяту и получать ароматную зелень.', 'text' => 'Мяту удобно выращивать в контейнере, чтобы корневища не захватывали соседние грядки.', 'views' => 410, 'categories' => ['garden-plants', 'herbs-spices']],
        ['title' => 'Календула в цветнике', 'slug' => 'calendula-care', 'image' => 'resources/images/calendula-care.png', 'description' => 'Яркое и неприхотливое растение для сада.', 'text' => 'Календула легко выращивается из семян, долго цветет и дает сырье для домашних сборов.', 'views' => 330, 'categories' => ['garden-plants', 'medicinal-plants']],
        ['title' => 'Острый перец на подоконнике', 'slug' => 'indoor-chili-pepper', 'image' => 'resources/images/indoor-chili-pepper.png', 'description' => 'Полный цикл выращивания острого перца в горшке.', 'text' => 'Компактные сорта перца подходят для солнечного подоконника и дают урожай при регулярных подкормках.', 'views' => 610, 'categories' => ['indoor-plants', 'vegetable-crops']],
        ['title' => 'Монстера: крупные листья дома', 'slug' => 'monstera-care', 'image' => 'resources/images/monstera-care.png', 'description' => 'Свет, опора и влажность для монстеры.', 'text' => 'Монстере нужен яркий рассеянный свет, опора для воздушных корней и равномерный полив.', 'views' => 920, 'categories' => ['indoor-plants']],
        ['title' => 'Спатифиллум: цветение и уход', 'slug' => 'peace-lily-care', 'image' => 'resources/images/peace-lily-care.png', 'description' => 'Почему спатифиллум не цветет и как ему помочь.', 'text' => 'Спатифиллум цветет при мягком рассеянном свете, тепле и без пересушивания корней.', 'views' => 760, 'categories' => ['indoor-plants']],
        ['title' => 'Фикус каучуконосный', 'slug' => 'rubber-plant-care', 'image' => 'resources/images/rubber-plant-care.png', 'description' => 'Уход за фикусом с крупными глянцевыми листьями.', 'text' => 'Фикус предпочитает стабильные условия, умеренный полив и регулярное удаление пыли с листьев.', 'views' => 690, 'categories' => ['indoor-plants']],
        ['title' => 'Садовые розы: здоровое цветение', 'slug' => 'garden-rose-care', 'image' => 'resources/images/garden-rose-care.png', 'description' => 'Посадка, подкормка и обрезка садовых роз.', 'text' => 'Розам нужны солнечное место, питательная почва и хорошая циркуляция воздуха между побегами.', 'views' => 840, 'categories' => ['garden-plants']],
        ['title' => 'Пионы: посадка на десятилетия', 'slug' => 'peony-growing', 'image' => 'resources/images/peony-growing.png', 'description' => 'Выбор места и глубины посадки для пионов.', 'text' => 'Пионы не любят частых пересадок. Почки возобновления располагают неглубоко, чтобы растение цвело.', 'views' => 570, 'categories' => ['garden-plants']],
        ['title' => 'Голубая гортензия', 'slug' => 'hydrangea-care', 'image' => 'resources/images/hydrangea-care.png', 'description' => 'Как кислотность почвы влияет на окраску гортензии.', 'text' => 'На кислой почве крупнолистные гортензии могут давать голубые соцветия. Важны также влага и полутень.', 'views' => 640, 'categories' => ['garden-plants']],
        ['title' => 'Эхинокактус: золотой шар', 'slug' => 'golden-barrel-cactus', 'image' => 'resources/images/golden-barrel-cactus.png', 'description' => 'Солнце, зимовка и полив эхинокактуса.', 'text' => 'Эхинокактусу нужно много солнца, минеральный субстрат и прохладная сухая зимовка.', 'views' => 450, 'categories' => ['succulents-cacti']],
        ['title' => 'Толстянка: формирование кроны', 'slug' => 'crassula-care', 'image' => null, 'description' => 'Уход за денежным деревом и его обрезка.', 'text' => 'Толстянка хорошо ветвится после прищипки. Между поливами грунт должен полностью просыхать.', 'views' => 390, 'categories' => ['succulents-cacti']],
        ['title' => 'Хавортия для небольшого подоконника', 'slug' => 'haworthia-care', 'image' => null, 'description' => 'Компактный суккулент для рассеянного света.', 'text' => 'Хавортия лучше многих суккулентов переносит полутень, но также не терпит застоя воды.', 'views' => 260, 'categories' => ['succulents-cacti']],
        ['title' => 'Аптечная ромашка', 'slug' => 'chamomile-growing', 'image' => null, 'description' => 'Выращивание и правильный сбор цветков ромашки.', 'text' => 'Ромашку высевают на солнечном участке, а корзинки собирают в сухую погоду в начале цветения.', 'views' => 300, 'categories' => ['medicinal-plants']],
        ['title' => 'Эхинацея пурпурная', 'slug' => 'echinacea-growing', 'image' => null, 'description' => 'Лекарственный многолетник с ярким цветением.', 'text' => 'Эхинацея зимостойка, привлекает опылителей и лучше цветет на солнечных местах без застоя влаги.', 'views' => 340, 'categories' => ['medicinal-plants']],
        ['title' => 'Зверобой на садовом участке', 'slug' => 'st-johns-wort', 'image' => null, 'description' => 'Солнечный многолетник и его заготовка.', 'text' => 'Зверобой неприхотлив, но ему нужно солнце. Верхушки срезают в период массового цветения.', 'views' => 210, 'categories' => ['medicinal-plants']],
        ['title' => 'Молодой дуб в саду', 'slug' => 'young-oak-tree', 'image' => null, 'description' => 'Выбор места и уход за молодым дубом.', 'text' => 'Дубу нужно просторное место и глубокая почва. В первые годы важны полив и защита от повреждений.', 'views' => 190, 'categories' => ['trees-shrubs']],
        ['title' => 'Можжевельник в ландшафте', 'slug' => 'juniper-landscape', 'image' => null, 'description' => 'Выбор сорта можжевельника для участка.', 'text' => 'Можжевельники различаются высотой и формой. Большинству нужны солнце и дренированная почва.', 'views' => 270, 'categories' => ['trees-shrubs']],
        ['title' => 'Сирень: обрезка после цветения', 'slug' => 'lilac-pruning', 'image' => null, 'description' => 'Как сохранить форму и цветение сирени.', 'text' => 'Сирень обрезают сразу после цветения, чтобы растение успело заложить цветочные почки на следующий год.', 'views' => 430, 'categories' => ['trees-shrubs']],
        ['title' => 'Томаты: от рассады до урожая', 'slug' => 'tomato-growing', 'image' => null, 'description' => 'Питание, формировка и защита томатов.', 'text' => 'Томатам нужны солнце, тепло, равномерный полив и подвязка. Сорт выбирают с учетом теплицы или открытого грунта.', 'views' => 880, 'categories' => ['vegetable-crops']],
        ['title' => 'Огурцы на шпалере', 'slug' => 'cucumber-trellis', 'image' => null, 'description' => 'Вертикальное выращивание огурцов и уход за ними.', 'text' => 'Шпалера улучшает проветривание огурцов, упрощает сбор и снижает риск грибных болезней.', 'views' => 720, 'categories' => ['vegetable-crops']],
        ['title' => 'Морковь на рыхлой грядке', 'slug' => 'carrot-growing', 'image' => null, 'description' => 'Почва, прореживание и сроки уборки моркови.', 'text' => 'Для ровных корнеплодов нужна глубоко рыхлая почва без камней, а всходы важно вовремя проредить.', 'views' => 510, 'categories' => ['vegetable-crops']],
        ['title' => 'Базилик: сбор все лето', 'slug' => 'basil-harvest', 'image' => null, 'description' => 'Прищипка базилика для густого роста.', 'text' => 'Цветоносы базилика удаляют, а верхушки регулярно прищипывают. Это стимулирует боковые побеги.', 'views' => 670, 'categories' => ['herbs-spices']],
        ['title' => 'Укроп без прерыва', 'slug' => 'dill-growing', 'image' => null, 'description' => 'Поэтапные посевы укропа для постоянной зелени.', 'text' => 'Укроп высевают небольшими порциями каждые две-три недели, чтобы молодая зелень была доступна все лето.', 'views' => 350, 'categories' => ['herbs-spices']],
        ['title' => 'Петрушка: двухлетний цикл', 'slug' => 'parsley-growing', 'image' => null, 'description' => 'Посев петрушки и использование ее второго года.', 'text' => 'Семена петрушки всходят медленно. Замачивание ускоряет процесс, а на второй год растение дает раннюю зелень.', 'views' => 320, 'categories' => ['herbs-spices']],
    ];

    public function load(ObjectManager $manager): void
    {
        $publishedAt = new DateTimeImmutable('2026-11-20 12:00:00');

        foreach (self::ARTICLES as $index => $data) {
            $article = new Article(
                title: $data['title'],
                slug: $data['slug'],
                image: $data['image'] === null
                    ? null
                    : str_replace('resources/images/', '/assets/images/', $data['image']),
                description: $data['description'],
                text: $data['text'],
                publishedAt: $publishedAt->modify(sprintf('-%d days', $index * 2)),
                views: $data['views'],
            );

            foreach ($data['categories'] as $categoryIndex => $categorySlug) {
                $article->addCategory(
                    category: $this->getReference(
                        CategoryFixture::reference($categorySlug),
                        Category::class,
                    ),
                    isPrimary: $categoryIndex === 0,
                );
            }

            $manager->persist($article);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixture::class];
    }
}
