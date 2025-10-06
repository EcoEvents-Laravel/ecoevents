<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Blog;
use App\Models\Tag;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur de test
        $user = User::firstOrCreate(
            ['email' => 'admin@ecoevents.com'],
            [
                'name' => 'Admin EcoEvents',
                'password' => bcrypt('password123'),
            ]
        );

        // Créer des tags
        $tags = [
            'Durabilité',
            'Innovation',
            'Événements',
            'Tech',
            'Communauté',
            'Éco-design',
        ];

        $tagModels = [];
        foreach ($tags as $tagName) {
            $tagModels[] = Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName]
            );
        }

        // Créer des articles de blog
        $articles = [
            [
                'title' => 'Comment organiser un festival zéro déchet en 2025',
                'content' => "L'organisation d'événements éco-responsables n'a jamais été aussi importante. Dans cet article, nous allons explorer les meilleures pratiques pour réduire l'impact environnemental de vos festivals.\n\nPremièrement, il est essentiel de travailler avec des fournisseurs locaux qui partagent vos valeurs écologiques. Ensuite, privilégiez les matériaux réutilisables et compostables pour tous les aspects de votre événement.\n\nLa sensibilisation du public est également cruciale. Organisez des ateliers sur le recyclage et la réduction des déchets. Installez des stations de tri clairement indiquées et engagez des bénévoles pour aider les participants.\n\nEnfin, mesurez votre impact. Calculez l'empreinte carbone de votre événement et communiquez vos résultats de manière transparente. Cela inspire confiance et encourage d'autres organisateurs à suivre votre exemple.",
                'status' => 'published',
                'tags' => [0, 2, 5],
            ],
            [
                'title' => 'Les technologies vertes au service de l\'événementiel',
                'content' => "La technologie peut être un puissant allié dans la création d'événements durables. Des applications de covoiturage aux billets électroniques, les solutions numériques réduisent considérablement l'empreinte écologique.\n\nLes panneaux solaires portables permettent désormais d'alimenter des scènes entières en énergie renouvelable. Les systèmes de gestion intelligente des ressources optimisent la consommation d'eau et d'électricité en temps réel.\n\nLa réalité virtuelle ouvre également de nouvelles possibilités. Pourquoi ne pas proposer des visites virtuelles de votre lieu avant l'événement, réduisant ainsi les déplacements inutiles ?\n\nN'oublions pas les outils de communication digitale qui permettent de minimiser l'utilisation de supports papier. Les brochures numériques, les QR codes et les applications dédiées sont désormais la norme.",
                'status' => 'published',
                'tags' => [1, 3],
            ],
            [
                'title' => 'Créer une communauté engagée autour de vos événements',
                'content' => "Un événement durable ne se limite pas à des pratiques écologiques ; il s'agit aussi de créer du lien social et de fédérer une communauté.\n\nCommencez par identifier vos valeurs communes et communiquez-les clairement. Vos participants doivent comprendre pourquoi ils font partie de quelque chose de plus grand.\n\nOrganisez des rencontres régulières, même virtuelles, pour maintenir l'engagement entre les événements. Créez des groupes de discussion, des forums ou des réseaux sociaux dédiés.\n\nEncouragez la participation active. Donnez à vos membres l'opportunité de contribuer à l'organisation, de proposer des idées et de prendre des responsabilités.\n\nCélébrez les succès collectifs et reconnaissez les contributions individuelles. Une communauté forte est celle où chacun se sent valorisé et entendu.",
                'status' => 'published',
                'tags' => [4, 2],
            ],
            [
                'title' => 'Le futur de l\'événementiel durable : tendances 2025-2030',
                'content' => "À quoi ressemblera l'événementiel dans les prochaines années ? Les tendances actuelles dessinent un avenir prometteur pour les organisateurs engagés.\n\nL'économie circulaire deviendra la norme. Les événements éphémères laisseront place à des infrastructures modulables et réutilisables. Les déchets d'un événement deviendront les ressources d'un autre.\n\nLa transparence sera exigée par les participants. Les bilans carbone détaillés, les certifications écologiques et les rapports d'impact seront indispensables pour maintenir la confiance du public.\n\nL'hybridation entre physique et virtuel se généralisera. Les événements proposeront systématiquement une option de participation à distance, réduisant les déplacements tout en élargissant l'audience.\n\nEnfin, l'intelligence artificielle optimisera la logistique événementielle, minimisant le gaspillage et maximisant l'efficacité énergétique.",
                'status' => 'draft',
                'tags' => [1, 0, 3],
            ],
        ];

        foreach ($articles as $articleData) {
            $blog = Blog::create([
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'status' => $articleData['status'],
                'publication_date' => $articleData['status'] === 'published' ? now()->subDays(rand(1, 30)) : null,
                'author_id' => $user->id,
            ]);

            // Attacher les tags
            $selectedTags = collect($articleData['tags'])->map(fn($index) => $tagModels[$index]->id);
            $blog->tags()->attach($selectedTags);
        }

        $this->command->info('✅ Blog seeder completed successfully!');
        $this->command->info('📝 Created: ' . count($articles) . ' blog posts');
        $this->command->info('🏷️ Created: ' . count($tags) . ' tags');
        $this->command->info('👤 User: admin@ecoevents.com / password: password123');
    }
}