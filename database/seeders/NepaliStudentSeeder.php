<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Support\Facades\Hash;

class NepaliStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Nepali student users
        $students = [
            [
                'name' => 'Ram Sharma',
                'email' => 'ram.sharma@student.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'type' => 'ai_student'
            ],
            [
                'name' => 'Sita Poudel',
                'email' => 'sita.poudel@student.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'type' => 'programming_student'
            ],
            [
                'name' => 'Hari Thapa',
                'email' => 'hari.thapa@student.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'type' => 'general_student'
            ],
            [
                'name' => 'Gita Gurung',
                'email' => 'gita.gurung@student.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'type' => 'data_science_student'
            ],
            [
                'name' => 'Krishna Shrestha',
                'email' => 'krishna.shrestha@student.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'type' => 'web_dev_student'
            ]
        ];

        foreach ($students as $studentData) {
            $type = $studentData['type'];
            unset($studentData['type']); // Remove type before creating user
            $user = User::updateOrCreate(
                ['email' => $studentData['email']],
                $studentData
            );
            $this->createStudentContent($user, $type);
        }
    }

    private function createStudentContent(User $user, string $type): void
    {
        switch ($type) {
            case 'ai_student':
                $this->createAIStudentContent($user);
                break;
            case 'programming_student':
                $this->createProgrammingStudentContent($user);
                break;
            case 'general_student':
                $this->createGeneralStudentContent($user);
                break;
            case 'data_science_student':
                $this->createDataScienceStudentContent($user);
                break;
            case 'web_dev_student':
                $this->createWebDevStudentContent($user);
                break;
        }
    }

    private function createAIStudentContent(User $user): void
    {
        // Create AI/ML specific categories
        $aiCategory = Category::create([
            'name' => 'AI & Machine Learning',
            'color' => '#e74c3c',
            'icon' => 'robot',
            'user_id' => $user->id,
            'sort_order' => 1
        ]);

        $researchCategory = Category::create([
            'name' => 'Research Papers',
            'color' => '#9b59b6',
            'icon' => 'file-text',
            'user_id' => $user->id,
            'sort_order' => 2
        ]);

        $toolsCategory = Category::create([
            'name' => 'AI Tools & Platforms',
            'color' => '#3498db',
            'icon' => 'wrench',
            'user_id' => $user->id,
            'sort_order' => 3
        ]);

        // Create AI/ML specific tags
        $aiTags = [
            'Machine Learning', 'Deep Learning', 'Neural Networks', 'NLP', 'Computer Vision',
            'TensorFlow', 'PyTorch', 'Keras', 'Research', 'Papers', 'AI Ethics', 'Datasets'
        ];

        $tags = [];
        foreach ($aiTags as $tagName) {
            $tags[] = Tag::firstOrCreate([
                'name' => $tagName
            ], [
                'usage_count' => 0
            ]);
        }

        // AI/ML Bookmarks
        $aiBookmarks = [
            [
                'title' => 'Papers With Code',
                'url' => 'https://paperswithcode.com/',
                'description' => 'Latest machine learning papers with code implementations',
                'category_id' => $researchCategory->id,
                'tags' => ['Machine Learning', 'Research', 'Papers']
            ],
            [
                'title' => 'Google AI Research',
                'url' => 'https://ai.google/research/',
                'description' => 'Google\'s AI research publications and breakthroughs',
                'category_id' => $researchCategory->id,
                'tags' => ['Research', 'AI Ethics', 'Papers']
            ],
            [
                'title' => 'OpenAI',
                'url' => 'https://openai.com/',
                'description' => 'OpenAI research and AI models including GPT and DALL-E',
                'category_id' => $aiCategory->id,
                'tags' => ['Deep Learning', 'NLP', 'Research']
            ],
            [
                'title' => 'TensorFlow',
                'url' => 'https://tensorflow.org/',
                'description' => 'Open source machine learning platform',
                'category_id' => $toolsCategory->id,
                'tags' => ['TensorFlow', 'Machine Learning', 'Deep Learning']
            ],
            [
                'title' => 'PyTorch',
                'url' => 'https://pytorch.org/',
                'description' => 'Deep learning framework for fast, flexible experimentation',
                'category_id' => $toolsCategory->id,
                'tags' => ['PyTorch', 'Deep Learning', 'Neural Networks']
            ],
            [
                'title' => 'Kaggle',
                'url' => 'https://kaggle.com/',
                'description' => 'Machine learning competitions and datasets',
                'category_id' => $toolsCategory->id,
                'tags' => ['Datasets', 'Machine Learning', 'Research']
            ],
            [
                'title' => 'Hugging Face',
                'url' => 'https://huggingface.co/',
                'description' => 'Natural language processing models and datasets',
                'category_id' => $toolsCategory->id,
                'tags' => ['NLP', 'Deep Learning', 'Datasets']
            ],
            [
                'title' => 'ArXiv.org',
                'url' => 'https://arxiv.org/list/cs.AI/recent',
                'description' => 'AI and ML research papers archive',
                'category_id' => $researchCategory->id,
                'tags' => ['Research', 'Papers', 'Machine Learning']
            ],
            [
                'title' => 'Towards Data Science',
                'url' => 'https://towardsdatascience.com/',
                'description' => 'Medium publication for data science and ML articles',
                'category_id' => $aiCategory->id,
                'tags' => ['Machine Learning', 'Deep Learning', 'Research']
            ],
            [
                'title' => 'Google Colab',
                'url' => 'https://colab.research.google.com/',
                'description' => 'Free Jupyter notebook environment for ML experiments',
                'category_id' => $toolsCategory->id,
                'tags' => ['TensorFlow', 'PyTorch', 'Machine Learning']
            ],
            [
                'title' => 'OpenCV',
                'url' => 'https://opencv.org/',
                'description' => 'Computer vision library for image processing',
                'category_id' => $toolsCategory->id,
                'tags' => ['Computer Vision', 'Machine Learning']
            ],
            [
                'title' => 'MIT OpenCourseWare - AI',
                'url' => 'https://ocw.mit.edu/courses/electrical-engineering-and-computer-science/6-034-artificial-intelligence-fall-2010/',
                'description' => 'MIT\'s free AI course materials',
                'category_id' => $aiCategory->id,
                'tags' => ['Machine Learning', 'Research', 'AI Ethics']
            ]
        ];

        $this->createBookmarks($user, $aiBookmarks, $tags);
    }

    private function createProgrammingStudentContent(User $user): void
    {
        // Create programming specific categories
        $webDevCategory = Category::create([
            'name' => 'Web Development',
            'color' => '#2ecc71',
            'icon' => 'code',
            'user_id' => $user->id,
            'sort_order' => 1
        ]);

        $algorithmsCategory = Category::create([
            'name' => 'Algorithms & Data Structures',
            'color' => '#f39c12',
            'icon' => 'tree',
            'user_id' => $user->id,
            'sort_order' => 2
        ]);

        $languagesCategory = Category::create([
            'name' => 'Programming Languages',
            'color' => '#e67e22',
            'icon' => 'bookmark',
            'user_id' => $user->id,
            'sort_order' => 3
        ]);

        // Programming tags
        $progTags = [
            'JavaScript', 'Python', 'Java', 'C++', 'React', 'Node.js', 'HTML', 'CSS',
            'Algorithms', 'Data Structures', 'Git', 'GitHub', 'APIs', 'Databases'
        ];

        $tags = [];
        foreach ($progTags as $tagName) {
            $tags[] = Tag::firstOrCreate([
                'name' => $tagName
            ], [
                'usage_count' => 0
            ]);
        }

        // Programming Bookmarks
        $progBookmarks = [
            [
                'title' => 'MDN Web Docs',
                'url' => 'https://developer.mozilla.org/',
                'description' => 'Comprehensive web development documentation',
                'category_id' => $webDevCategory->id,
                'tags' => ['JavaScript', 'HTML', 'CSS']
            ],
            [
                'title' => 'Stack Overflow',
                'url' => 'https://stackoverflow.com/',
                'description' => 'Programming Q&A community',
                'category_id' => $languagesCategory->id,
                'tags' => ['JavaScript', 'Python', 'Java']
            ],
            [
                'title' => 'GitHub',
                'url' => 'https://github.com/',
                'description' => 'Code hosting and version control platform',
                'category_id' => $languagesCategory->id,
                'tags' => ['Git', 'GitHub']
            ],
            [
                'title' => 'LeetCode',
                'url' => 'https://leetcode.com/',
                'description' => 'Coding interview preparation platform',
                'category_id' => $algorithmsCategory->id,
                'tags' => ['Algorithms', 'Data Structures', 'Python', 'Java']
            ],
            [
                'title' => 'HackerRank',
                'url' => 'https://hackerrank.com/',
                'description' => 'Programming challenges and skill assessment',
                'category_id' => $algorithmsCategory->id,
                'tags' => ['Algorithms', 'Python', 'C++', 'Java']
            ],
            [
                'title' => 'freeCodeCamp',
                'url' => 'https://freecodecamp.org/',
                'description' => 'Free coding bootcamp with interactive lessons',
                'category_id' => $webDevCategory->id,
                'tags' => ['JavaScript', 'HTML', 'CSS', 'React']
            ],
            [
                'title' => 'W3Schools',
                'url' => 'https://w3schools.com/',
                'description' => 'Web development tutorials and references',
                'category_id' => $webDevCategory->id,
                'tags' => ['HTML', 'CSS', 'JavaScript']
            ],
            [
                'title' => 'Python.org',
                'url' => 'https://python.org/',
                'description' => 'Official Python programming language website',
                'category_id' => $languagesCategory->id,
                'tags' => ['Python']
            ],
            [
                'title' => 'React Documentation',
                'url' => 'https://react.dev/',
                'description' => 'Official React library documentation',
                'category_id' => $webDevCategory->id,
                'tags' => ['React', 'JavaScript']
            ],
            [
                'title' => 'Node.js',
                'url' => 'https://nodejs.org/',
                'description' => 'JavaScript runtime for server-side development',
                'category_id' => $webDevCategory->id,
                'tags' => ['Node.js', 'JavaScript']
            ],
            [
                'title' => 'GeeksforGeeks',
                'url' => 'https://geeksforgeeks.org/',
                'description' => 'Programming tutorials and interview preparation',
                'category_id' => $algorithmsCategory->id,
                'tags' => ['Algorithms', 'Data Structures', 'Java', 'Python']
            ],
            [
                'title' => 'Codecademy',
                'url' => 'https://codecademy.com/',
                'description' => 'Interactive coding lessons and courses',
                'category_id' => $languagesCategory->id,
                'tags' => ['Python', 'JavaScript', 'HTML', 'CSS']
            ]
        ];

        $this->createBookmarks($user, $progBookmarks, $tags);
    }

    private function createGeneralStudentContent(User $user): void
    {
        // General education categories
        $educationCategory = Category::create([
            'name' => 'Online Education',
            'color' => '#1abc9c',
            'icon' => 'graduation-cap',
            'user_id' => $user->id,
            'sort_order' => 1
        ]);

        $studyCategory = Category::create([
            'name' => 'Study Resources',
            'color' => '#34495e',
            'icon' => 'book',
            'user_id' => $user->id,
            'sort_order' => 2
        ]);

        // General study tags
        $studyTags = [
            'Education', 'Online Learning', 'Study Tools', 'Academic', 'Research',
            'Mathematics', 'Science', 'Language Learning', 'Productivity'
        ];

        $tags = [];
        foreach ($studyTags as $tagName) {
            $tags[] = Tag::firstOrCreate([
                'name' => $tagName
            ], [
                'usage_count' => 0
            ]);
        }

        // General education bookmarks
        $eduBookmarks = [
            [
                'title' => 'Khan Academy',
                'url' => 'https://khanacademy.org/',
                'description' => 'Free online courses and lessons',
                'category_id' => $educationCategory->id,
                'tags' => ['Education', 'Online Learning', 'Mathematics']
            ],
            [
                'title' => 'Coursera',
                'url' => 'https://coursera.org/',
                'description' => 'University courses and professional certificates',
                'category_id' => $educationCategory->id,
                'tags' => ['Education', 'Online Learning', 'Academic']
            ],
            [
                'title' => 'edX',
                'url' => 'https://edx.org/',
                'description' => 'University-level online courses',
                'category_id' => $educationCategory->id,
                'tags' => ['Education', 'Academic', 'Science']
            ],
            [
                'title' => 'Quizlet',
                'url' => 'https://quizlet.com/',
                'description' => 'Flashcards and study tools',
                'category_id' => $studyCategory->id,
                'tags' => ['Study Tools', 'Education']
            ],
            [
                'title' => 'Google Scholar',
                'url' => 'https://scholar.google.com/',
                'description' => 'Academic search engine for scholarly articles',
                'category_id' => $studyCategory->id,
                'tags' => ['Research', 'Academic']
            ],
            [
                'title' => 'Duolingo',
                'url' => 'https://duolingo.com/',
                'description' => 'Language learning platform',
                'category_id' => $educationCategory->id,
                'tags' => ['Language Learning', 'Education']
            ],
            [
                'title' => 'Wolfram Alpha',
                'url' => 'https://wolframalpha.com/',
                'description' => 'Computational knowledge engine',
                'category_id' => $studyCategory->id,
                'tags' => ['Mathematics', 'Science', 'Study Tools']
            ],
            [
                'title' => 'TED-Ed',
                'url' => 'https://ed.ted.com/',
                'description' => 'Educational videos and lessons',
                'category_id' => $educationCategory->id,
                'tags' => ['Education', 'Online Learning']
            ]
        ];

        $this->createBookmarks($user, $eduBookmarks, $tags);
    }

    private function createDataScienceStudentContent(User $user): void
    {
        // Data Science categories
        $dataCategory = Category::create([
            'name' => 'Data Science',
            'color' => '#8e44ad',
            'icon' => 'bar-chart',
            'user_id' => $user->id,
            'sort_order' => 1
        ]);

        // Data science tags
        $dataTags = ['Data Science', 'Statistics', 'R', 'Python', 'Pandas', 'NumPy', 'Matplotlib', 'Jupyter'];

        $tags = [];
        foreach ($dataTags as $tagName) {
            $tags[] = Tag::firstOrCreate([
                'name' => $tagName
            ], [
                'usage_count' => 0
            ]);
        }

        // Data science bookmarks
        $dataBookmarks = [
            [
                'title' => 'Jupyter',
                'url' => 'https://jupyter.org/',
                'description' => 'Interactive computing notebooks',
                'category_id' => $dataCategory->id,
                'tags' => ['Jupyter', 'Python', 'Data Science']
            ],
            [
                'title' => 'Pandas Documentation',
                'url' => 'https://pandas.pydata.org/',
                'description' => 'Python data manipulation library',
                'category_id' => $dataCategory->id,
                'tags' => ['Pandas', 'Python', 'Data Science']
            ],
            [
                'title' => 'R for Data Science',
                'url' => 'https://r4ds.had.co.nz/',
                'description' => 'Free book on R programming for data science',
                'category_id' => $dataCategory->id,
                'tags' => ['R', 'Data Science', 'Statistics']
            ]
        ];

        $this->createBookmarks($user, $dataBookmarks, $tags);
    }

    private function createWebDevStudentContent(User $user): void
    {
        // Web development categories
        $frontendCategory = Category::create([
            'name' => 'Frontend Development',
            'color' => '#3498db',
            'icon' => 'laptop',
            'user_id' => $user->id,
            'sort_order' => 1
        ]);

        // Web dev tags
        $webTags = ['Frontend', 'Backend', 'Vue.js', 'Angular', 'TypeScript', 'Sass', 'Webpack'];

        $tags = [];
        foreach ($webTags as $tagName) {
            $tags[] = Tag::firstOrCreate([
                'name' => $tagName
            ], [
                'usage_count' => 0
            ]);
        }

        // Web dev bookmarks
        $webBookmarks = [
            [
                'title' => 'Vue.js',
                'url' => 'https://vuejs.org/',
                'description' => 'Progressive JavaScript framework',
                'category_id' => $frontendCategory->id,
                'tags' => ['Vue.js', 'JavaScript', 'Frontend']
            ],
            [
                'title' => 'Angular',
                'url' => 'https://angular.io/',
                'description' => 'TypeScript-based web application framework',
                'category_id' => $frontendCategory->id,
                'tags' => ['Angular', 'TypeScript', 'Frontend']
            ],
            [
                'title' => 'Sass',
                'url' => 'https://sass-lang.com/',
                'description' => 'CSS extension language',
                'category_id' => $frontendCategory->id,
                'tags' => ['Sass', 'CSS', 'Frontend']
            ]
        ];

        $this->createBookmarks($user, $webBookmarks, $tags);
    }

    private function createBookmarks(User $user, array $bookmarks, array $tags): void
    {
        foreach ($bookmarks as $bookmarkData) {
            $bookmark = Bookmark::create([
                'title' => $bookmarkData['title'],
                'url' => $bookmarkData['url'],
                'description' => $bookmarkData['description'],
                'category_id' => $bookmarkData['category_id'],
                'user_id' => $user->id,
                'favorite' => rand(0, 1) === 1,
                'visits' => rand(0, 50),
                'status' => 'active'
            ]);

            // Attach tags
            if (isset($bookmarkData['tags'])) {
                $tagIds = [];
                foreach ($bookmarkData['tags'] as $tagName) {
                    $tag = collect($tags)->firstWhere('name', $tagName);
                    if ($tag) {
                        $tagIds[] = $tag->id;
                    }
                }
                $bookmark->tags()->attach($tagIds);
            }
        }
    }

    private function getRandomColor(): string
    {
        $colors = [
            '#e74c3c', '#3498db', '#2ecc71', '#f39c12', '#9b59b6',
            '#1abc9c', '#e67e22', '#34495e', '#f1c40f', '#e91e63'
        ];
        return $colors[array_rand($colors)];
    }
}
