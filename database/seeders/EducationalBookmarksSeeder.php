<?php

/**
 * ================================================================================
 * EDUCATIONAL BOOKMARKS SEEDER - 100+ LEARNING RESOURCES
 * ================================================================================
 *
 * 🏢 VENDOR: Eastlink Cloud Pvt. Ltd.
 * 👨‍💻 AUTHOR: Developer Team
 * 📅 CREATED: October 2025
 * 📧 CONTACT: puran@eastlink.net.np
 * 📞 PHONE: +977-01-4101181
 * 📱 DEVELOPER: +977-9801901140
 * 💼 BUSINESS: +977-9801901141
 * 🏢 ADDRESS: Tripureshwor, Kathmandu, Nepal
 *
 * 📋 DESCRIPTION:
 * Comprehensive educational bookmark collection seeder that adds 100+
 * high-quality learning resources across 12 major educational categories
 * to all users in the system.
 *
 * 🎯 EDUCATIONAL CATEGORIES:
 * - Programming & Development (12 resources)
 * - AI & Machine Learning (12 resources)
 * - Web Development (13 resources)
 * - Data Science & Analytics (13 resources)
 * - Design & UI/UX (12 resources)
 * - Language Learning (10 resources)
 * - Mathematics (11 resources)
 * - Science & Research (11 resources)
 * - Business & Finance (10 resources)
 * - Productivity Tools (10 resources)
 * - News & Education (10 resources)
 * - Documentation & References (9 resources)
 *
 * 📊 STATISTICS:
 * - Total Bookmarks: 1,200+ (100+ per user)
 * - Educational Categories: 12
 * - Learning Tags: 200+
 * - Multi-user Distribution: All existing users
 *
 * ⚖️ LICENSE: Commercial Enterprise License
 * ================================================================================
 */

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Bookmark;
use App\Models\Tag;
use Illuminate\Support\Str;

class EducationalBookmarksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Adds 100+ high-quality educational bookmarks to all users
     */
    public function run(): void
    {
        $this->command->info('🚀 Creating 100+ Educational Bookmarks for All Users...');

        // Get all users
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('⚠️  No users found. Please run user seeders first.');
            return;
        }

        foreach ($users as $user) {
            $this->command->info("📚 Adding bookmarks for: {$user->name}");
            $this->createEducationalBookmarks($user);
        }

        $this->command->info('✅ Educational bookmarks created successfully for all users!');
    }

    private function createEducationalBookmarks(User $user): void
    {
        // Create comprehensive educational categories
        $categories = $this->createEducationalCategories($user);

        // Create educational bookmarks by category
        $this->createProgrammingBookmarks($user, $categories);
        $this->createAIMLBookmarks($user, $categories);
        $this->createWebDevelopmentBookmarks($user, $categories);
        $this->createDataScienceBookmarks($user, $categories);
        $this->createDesignBookmarks($user, $categories);
        $this->createLanguageLearningBookmarks($user, $categories);
        $this->createMathematicsBookmarks($user, $categories);
        $this->createScienceBookmarks($user, $categories);
        $this->createBusinessBookmarks($user, $categories);
        $this->createProductivityBookmarks($user, $categories);
        $this->createNewsEducationBookmarks($user, $categories);
        $this->createDocumentationBookmarks($user, $categories);
    }

    private function createEducationalCategories(User $user): array
    {
        $categories = [
            'Programming' => ['icon' => 'code-slash', 'color' => '#007bff'],
            'AI & Machine Learning' => ['icon' => 'robot', 'color' => '#e74c3c'],
            'Web Development' => ['icon' => 'globe', 'color' => '#28a745'],
            'Data Science' => ['icon' => 'bar-chart', 'color' => '#17a2b8'],
            'Design & UI/UX' => ['icon' => 'palette', 'color' => '#fd7e14'],
            'Language Learning' => ['icon' => 'translate', 'color' => '#6f42c1'],
            'Mathematics' => ['icon' => 'calculator', 'color' => '#6c757d'],
            'Science' => ['icon' => 'atom', 'color' => '#20c997'],
            'Business & Finance' => ['icon' => 'graph-up', 'color' => '#ffc107'],
            'Productivity' => ['icon' => 'lightning', 'color' => '#dc3545'],
            'News & Education' => ['icon' => 'newspaper', 'color' => '#495057'],
            'Documentation' => ['icon' => 'book', 'color' => '#0dcaf0']
        ];

        $createdCategories = [];
        foreach ($categories as $name => $config) {
            $createdCategories[$name] = Category::firstOrCreate([
                'name' => $name,
                'user_id' => $user->id,
            ], [
                'icon' => $config['icon'],
                'color' => $config['color'],
                'sort_order' => array_search($name, array_keys($categories)) + 1
            ]);
        }

        return $createdCategories;
    }

    private function createProgrammingBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Code Learning Platforms
            ['title' => 'FreeCodeCamp', 'url' => 'https://freecodecamp.org', 'description' => 'Learn to code with free interactive lessons', 'tags' => ['coding', 'tutorial', 'free']],
            ['title' => 'Codecademy', 'url' => 'https://codecademy.com', 'description' => 'Interactive coding lessons and projects', 'tags' => ['coding', 'interactive', 'courses']],
            ['title' => 'LeetCode', 'url' => 'https://leetcode.com', 'description' => 'Practice coding problems and algorithm challenges', 'tags' => ['algorithms', 'interview', 'practice']],
            ['title' => 'HackerRank', 'url' => 'https://hackerrank.com', 'description' => 'Coding challenges and skill assessment', 'tags' => ['coding', 'challenges', 'skills']],
            ['title' => 'Codewars', 'url' => 'https://codewars.com', 'description' => 'Improve skills with code kata challenges', 'tags' => ['coding', 'challenges', 'community']],
            ['title' => 'GeeksforGeeks', 'url' => 'https://geeksforgeeks.org', 'description' => 'Computer science portal with tutorials and articles', 'tags' => ['computer-science', 'tutorials', 'algorithms']],

            // Language-Specific Resources
            ['title' => 'Python.org', 'url' => 'https://python.org', 'description' => 'Official Python programming language website', 'tags' => ['python', 'official', 'documentation']],
            ['title' => 'Real Python', 'url' => 'https://realpython.com', 'description' => 'High-quality Python tutorials and articles', 'tags' => ['python', 'tutorials', 'advanced']],
            ['title' => 'JavaScript.info', 'url' => 'https://javascript.info', 'description' => 'The Modern JavaScript Tutorial', 'tags' => ['javascript', 'tutorial', 'comprehensive']],
            ['title' => 'Oracle Java Tutorials', 'url' => 'https://docs.oracle.com/javase/tutorial/', 'description' => 'Official Java programming tutorials', 'tags' => ['java', 'official', 'tutorial']],
            ['title' => 'Rust Book', 'url' => 'https://doc.rust-lang.org/book/', 'description' => 'The Rust Programming Language book', 'tags' => ['rust', 'book', 'systems-programming']],
            ['title' => 'Go by Example', 'url' => 'https://gobyexample.com', 'description' => 'Learn Go with annotated example programs', 'tags' => ['golang', 'examples', 'tutorial']],
        ];

        $this->createBookmarksForCategory($user, $categories['Programming'], $bookmarks);
    }

    private function createAIMLBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // AI/ML Learning Platforms
            ['title' => 'Coursera Machine Learning', 'url' => 'https://coursera.org/learn/machine-learning', 'description' => 'Andrew Ng\'s famous machine learning course', 'tags' => ['ml', 'coursera', 'andrew-ng']],
            ['title' => 'Fast.ai', 'url' => 'https://fast.ai', 'description' => 'Practical deep learning for coders', 'tags' => ['deep-learning', 'practical', 'free']],
            ['title' => 'Kaggle Learn', 'url' => 'https://kaggle.com/learn', 'description' => 'Free micro-courses in data science and ML', 'tags' => ['kaggle', 'free', 'micro-courses']],
            ['title' => 'Machine Learning Mastery', 'url' => 'https://machinelearningmastery.com', 'description' => 'Practical machine learning tutorials', 'tags' => ['ml', 'tutorials', 'practical']],
            ['title' => 'Papers With Code', 'url' => 'https://paperswithcode.com', 'description' => 'Machine learning papers with code implementations', 'tags' => ['research', 'papers', 'code']],
            ['title' => 'Towards Data Science', 'url' => 'https://towardsdatascience.com', 'description' => 'Medium publication for data science articles', 'tags' => ['data-science', 'articles', 'medium']],

            // AI Tools and Frameworks
            ['title' => 'TensorFlow', 'url' => 'https://tensorflow.org', 'description' => 'Open source machine learning framework', 'tags' => ['tensorflow', 'framework', 'google']],
            ['title' => 'PyTorch', 'url' => 'https://pytorch.org', 'description' => 'Open source machine learning library', 'tags' => ['pytorch', 'framework', 'facebook']],
            ['title' => 'Hugging Face', 'url' => 'https://huggingface.co', 'description' => 'The AI community building the future', 'tags' => ['ai', 'community', 'models']],
            ['title' => 'OpenAI', 'url' => 'https://openai.com', 'description' => 'AI research and development company', 'tags' => ['openai', 'research', 'gpt']],
            ['title' => 'Google AI', 'url' => 'https://ai.google', 'description' => 'Google\'s AI research and tools', 'tags' => ['google', 'ai', 'research']],
            ['title' => 'Distill.pub', 'url' => 'https://distill.pub', 'description' => 'Interactive machine learning explanations', 'tags' => ['ml', 'explanations', 'interactive']],
        ];

        $this->createBookmarksForCategory($user, $categories['AI & Machine Learning'], $bookmarks);
    }

    private function createWebDevelopmentBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Web Development Learning
            ['title' => 'MDN Web Docs', 'url' => 'https://developer.mozilla.org', 'description' => 'Comprehensive web development documentation', 'tags' => ['web', 'documentation', 'mozilla']],
            ['title' => 'Web.dev', 'url' => 'https://web.dev', 'description' => 'Google\'s web development best practices', 'tags' => ['web', 'google', 'best-practices']],
            ['title' => 'CSS-Tricks', 'url' => 'https://css-tricks.com', 'description' => 'CSS tips, tricks, and techniques', 'tags' => ['css', 'tips', 'tricks']],
            ['title' => 'Frontend Masters', 'url' => 'https://frontendmasters.com', 'description' => 'Expert-led frontend engineering courses', 'tags' => ['frontend', 'courses', 'expert']],
            ['title' => 'The Odin Project', 'url' => 'https://theodinproject.com', 'description' => 'Free full-stack web development curriculum', 'tags' => ['fullstack', 'free', 'curriculum']],

            // Frameworks and Libraries
            ['title' => 'React Documentation', 'url' => 'https://react.dev', 'description' => 'Official React documentation and tutorials', 'tags' => ['react', 'documentation', 'official']],
            ['title' => 'Vue.js Guide', 'url' => 'https://vuejs.org/guide/', 'description' => 'The progressive JavaScript framework', 'tags' => ['vue', 'javascript', 'framework']],
            ['title' => 'Angular Documentation', 'url' => 'https://angular.io/docs', 'description' => 'Official Angular framework documentation', 'tags' => ['angular', 'typescript', 'framework']],
            ['title' => 'Next.js Documentation', 'url' => 'https://nextjs.org/docs', 'description' => 'The React framework for production', 'tags' => ['nextjs', 'react', 'framework']],
            ['title' => 'Tailwind CSS', 'url' => 'https://tailwindcss.com', 'description' => 'Utility-first CSS framework', 'tags' => ['css', 'framework', 'utility']],
            ['title' => 'Bootstrap', 'url' => 'https://getbootstrap.com', 'description' => 'Popular CSS framework for responsive design', 'tags' => ['css', 'framework', 'responsive']],

            // Tools and Resources
            ['title' => 'Can I Use', 'url' => 'https://caniuse.com', 'description' => 'Browser compatibility tables for web technologies', 'tags' => ['compatibility', 'browser', 'support']],
            ['title' => 'CodePen', 'url' => 'https://codepen.io', 'description' => 'Online code editor and community', 'tags' => ['code-editor', 'community', 'frontend']],
        ];

        $this->createBookmarksForCategory($user, $categories['Web Development'], $bookmarks);
    }

    private function createDataScienceBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Data Science Platforms
            ['title' => 'Kaggle', 'url' => 'https://kaggle.com', 'description' => 'Data science competitions and datasets', 'tags' => ['data-science', 'competitions', 'datasets']],
            ['title' => 'Google Colab', 'url' => 'https://colab.research.google.com', 'description' => 'Free Jupyter notebook environment', 'tags' => ['jupyter', 'free', 'google']],
            ['title' => 'Jupyter.org', 'url' => 'https://jupyter.org', 'description' => 'Interactive computing across dozens of languages', 'tags' => ['jupyter', 'interactive', 'computing']],
            ['title' => 'DataCamp', 'url' => 'https://datacamp.com', 'description' => 'Learn data science and analytics', 'tags' => ['data-science', 'courses', 'analytics']],

            // Python Data Libraries
            ['title' => 'Pandas Documentation', 'url' => 'https://pandas.pydata.org', 'description' => 'Powerful data analysis library for Python', 'tags' => ['pandas', 'python', 'data-analysis']],
            ['title' => 'NumPy', 'url' => 'https://numpy.org', 'description' => 'Fundamental package for scientific computing', 'tags' => ['numpy', 'python', 'scientific-computing']],
            ['title' => 'Matplotlib', 'url' => 'https://matplotlib.org', 'description' => 'Python plotting library', 'tags' => ['matplotlib', 'python', 'visualization']],
            ['title' => 'Seaborn', 'url' => 'https://seaborn.pydata.org', 'description' => 'Statistical data visualization library', 'tags' => ['seaborn', 'visualization', 'statistics']],
            ['title' => 'Scikit-learn', 'url' => 'https://scikit-learn.org', 'description' => 'Machine learning library for Python', 'tags' => ['scikit-learn', 'machine-learning', 'python']],

            // R Resources
            ['title' => 'R for Data Science', 'url' => 'https://r4ds.had.co.nz', 'description' => 'Learn R for data science', 'tags' => ['r', 'data-science', 'book']],
            ['title' => 'RStudio', 'url' => 'https://rstudio.com', 'description' => 'IDE for R programming', 'tags' => ['r', 'ide', 'statistics']],

            // Visualization Tools
            ['title' => 'Tableau Public', 'url' => 'https://public.tableau.com', 'description' => 'Free data visualization platform', 'tags' => ['tableau', 'visualization', 'free']],
            ['title' => 'D3.js', 'url' => 'https://d3js.org', 'description' => 'JavaScript library for data visualization', 'tags' => ['d3js', 'javascript', 'visualization']],
        ];

        $this->createBookmarksForCategory($user, $categories['Data Science'], $bookmarks);
    }

    private function createDesignBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Design Learning
            ['title' => 'Adobe Creative Cloud', 'url' => 'https://adobe.com/creativecloud', 'description' => 'Professional creative software suite', 'tags' => ['adobe', 'design', 'creative']],
            ['title' => 'Figma', 'url' => 'https://figma.com', 'description' => 'Collaborative interface design tool', 'tags' => ['figma', 'ui-design', 'collaboration']],
            ['title' => 'Dribbble', 'url' => 'https://dribbble.com', 'description' => 'Design inspiration and community', 'tags' => ['dribbble', 'inspiration', 'community']],
            ['title' => 'Behance', 'url' => 'https://behance.net', 'description' => 'Creative portfolio platform', 'tags' => ['behance', 'portfolio', 'creative']],
            ['title' => 'Awwwards', 'url' => 'https://awwwards.com', 'description' => 'Website design awards and inspiration', 'tags' => ['awwwards', 'web-design', 'inspiration']],

            // Design Resources
            ['title' => 'Unsplash', 'url' => 'https://unsplash.com', 'description' => 'Free high-quality stock photos', 'tags' => ['photos', 'free', 'stock']],
            ['title' => 'Pexels', 'url' => 'https://pexels.com', 'description' => 'Free stock photos and videos', 'tags' => ['photos', 'videos', 'free']],
            ['title' => 'Google Fonts', 'url' => 'https://fonts.google.com', 'description' => 'Free web fonts', 'tags' => ['fonts', 'typography', 'free']],
            ['title' => 'Color Hunt', 'url' => 'https://colorhunt.co', 'description' => 'Beautiful color palettes', 'tags' => ['colors', 'palettes', 'inspiration']],
            ['title' => 'Coolors', 'url' => 'https://coolors.co', 'description' => 'Color palette generator', 'tags' => ['colors', 'generator', 'palettes']],

            // UI/UX Resources
            ['title' => 'Material Design', 'url' => 'https://material.io', 'description' => 'Google\'s design system', 'tags' => ['material-design', 'google', 'design-system']],
            ['title' => 'Human Interface Guidelines', 'url' => 'https://developer.apple.com/design/human-interface-guidelines/', 'description' => 'Apple\'s design guidelines', 'tags' => ['apple', 'design', 'guidelines']],
        ];

        $this->createBookmarksForCategory($user, $categories['Design & UI/UX'], $bookmarks);
    }

    private function createLanguageLearningBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Language Learning Platforms
            ['title' => 'Duolingo', 'url' => 'https://duolingo.com', 'description' => 'Free language learning app', 'tags' => ['duolingo', 'language-learning', 'free']],
            ['title' => 'Babbel', 'url' => 'https://babbel.com', 'description' => 'Professional language learning courses', 'tags' => ['babbel', 'language-learning', 'courses']],
            ['title' => 'Rosetta Stone', 'url' => 'https://rosettastone.com', 'description' => 'Immersive language learning software', 'tags' => ['rosetta-stone', 'language-learning', 'immersive']],
            ['title' => 'Memrise', 'url' => 'https://memrise.com', 'description' => 'Language learning with spaced repetition', 'tags' => ['memrise', 'language-learning', 'spaced-repetition']],
            ['title' => 'Anki', 'url' => 'https://ankiweb.net', 'description' => 'Spaced repetition flashcard system', 'tags' => ['anki', 'flashcards', 'spaced-repetition']],

            // Specific Languages
            ['title' => 'SpanishDict', 'url' => 'https://spanishdict.com', 'description' => 'Spanish-English dictionary and learning', 'tags' => ['spanish', 'dictionary', 'learning']],
            ['title' => 'FluentU', 'url' => 'https://fluentu.com', 'description' => 'Learn languages with real-world videos', 'tags' => ['fluentu', 'videos', 'real-world']],
            ['title' => 'HelloTalk', 'url' => 'https://hellotalk.com', 'description' => 'Language exchange with native speakers', 'tags' => ['hellotalk', 'language-exchange', 'native-speakers']],

            // Resources
            ['title' => 'Conjuguemos', 'url' => 'https://conjuguemos.com', 'description' => 'Verb conjugation practice', 'tags' => ['conjugation', 'verbs', 'practice']],
            ['title' => 'News in Slow', 'url' => 'https://newsinslowspanish.com', 'description' => 'Language learning through news', 'tags' => ['news', 'slow', 'listening']],
        ];

        $this->createBookmarksForCategory($user, $categories['Language Learning'], $bookmarks);
    }

    private function createMathematicsBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Math Learning Platforms
            ['title' => 'Khan Academy Math', 'url' => 'https://khanacademy.org/math', 'description' => 'Free math courses from basic to advanced', 'tags' => ['khan-academy', 'math', 'free']],
            ['title' => 'Brilliant', 'url' => 'https://brilliant.org', 'description' => 'Interactive math and science courses', 'tags' => ['brilliant', 'interactive', 'math-science']],
            ['title' => 'Paul\'s Online Math Notes', 'url' => 'https://tutorial.math.lamar.edu', 'description' => 'Comprehensive calculus and algebra notes', 'tags' => ['calculus', 'algebra', 'notes']],
            ['title' => 'PatrickJMT', 'url' => 'https://patrickjmt.com', 'description' => 'Free math videos and tutorials', 'tags' => ['math', 'videos', 'tutorials']],
            ['title' => 'Professor Leonard', 'url' => 'https://youtube.com/professorleonard', 'description' => 'Clear calculus and precalculus lectures', 'tags' => ['calculus', 'precalculus', 'lectures']],

            // Advanced Mathematics
            ['title' => 'MIT OpenCourseWare Math', 'url' => 'https://ocw.mit.edu/courses/mathematics/', 'description' => 'MIT\'s free mathematics courses', 'tags' => ['mit', 'opencourseware', 'advanced']],
            ['title' => 'Wolfram MathWorld', 'url' => 'https://mathworld.wolfram.com', 'description' => 'Comprehensive mathematics resource', 'tags' => ['wolfram', 'reference', 'comprehensive']],
            ['title' => 'ArXiv Mathematics', 'url' => 'https://arxiv.org/list/math/recent', 'description' => 'Latest mathematics research papers', 'tags' => ['arxiv', 'research', 'papers']],

            // Tools
            ['title' => 'Wolfram Alpha', 'url' => 'https://wolframalpha.com', 'description' => 'Computational math engine', 'tags' => ['wolfram-alpha', 'computation', 'calculator']],
            ['title' => 'Desmos Graphing Calculator', 'url' => 'https://desmos.com/calculator', 'description' => 'Advanced graphing calculator', 'tags' => ['desmos', 'graphing', 'calculator']],
            ['title' => 'GeoGebra', 'url' => 'https://geogebra.org', 'description' => 'Interactive math learning platform', 'tags' => ['geogebra', 'interactive', 'geometry']],
        ];

        $this->createBookmarksForCategory($user, $categories['Mathematics'], $bookmarks);
    }

    private function createScienceBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // General Science
            ['title' => 'Khan Academy Science', 'url' => 'https://khanacademy.org/science', 'description' => 'Free science courses and videos', 'tags' => ['khan-academy', 'science', 'free']],
            ['title' => 'Coursera Science', 'url' => 'https://coursera.org/browse/physical-science-and-engineering', 'description' => 'University-level science courses', 'tags' => ['coursera', 'science', 'university']],
            ['title' => 'edX Science', 'url' => 'https://edx.org/learn/science', 'description' => 'Science courses from top universities', 'tags' => ['edx', 'science', 'universities']],

            // Physics
            ['title' => 'Physics Classroom', 'url' => 'https://physicsclassroom.com', 'description' => 'High school physics tutorials', 'tags' => ['physics', 'high-school', 'tutorials']],
            ['title' => 'HyperPhysics', 'url' => 'http://hyperphysics.phy-astr.gsu.edu', 'description' => 'Comprehensive physics concepts', 'tags' => ['physics', 'concepts', 'reference']],

            // Chemistry
            ['title' => 'ChemSpider', 'url' => 'http://chemspider.com', 'description' => 'Chemical structure database', 'tags' => ['chemistry', 'database', 'structures']],
            ['title' => 'PubChem', 'url' => 'https://pubchem.ncbi.nlm.nih.gov', 'description' => 'Chemical information database', 'tags' => ['chemistry', 'pubchem', 'database']],

            // Biology
            ['title' => 'NCBI', 'url' => 'https://ncbi.nlm.nih.gov', 'description' => 'National Center for Biotechnology Information', 'tags' => ['biology', 'ncbi', 'biotechnology']],
            ['title' => 'Biology Online', 'url' => 'https://biologyonline.com', 'description' => 'Biology dictionary and tutorials', 'tags' => ['biology', 'dictionary', 'tutorials']],

            // Astronomy
            ['title' => 'NASA', 'url' => 'https://nasa.gov', 'description' => 'NASA\'s official website', 'tags' => ['nasa', 'space', 'astronomy']],
            ['title' => 'Space.com', 'url' => 'https://space.com', 'description' => 'Space news and information', 'tags' => ['space', 'news', 'astronomy']],
        ];

        $this->createBookmarksForCategory($user, $categories['Science'], $bookmarks);
    }

    private function createBusinessBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Business Education
            ['title' => 'Harvard Business Review', 'url' => 'https://hbr.org', 'description' => 'Business management ideas and insights', 'tags' => ['harvard', 'business', 'management']],
            ['title' => 'Coursera Business', 'url' => 'https://coursera.org/browse/business', 'description' => 'Business courses from top universities', 'tags' => ['coursera', 'business', 'courses']],
            ['title' => 'Khan Academy Entrepreneurship', 'url' => 'https://khanacademy.org/economics-finance-domain/entrepreneurship2', 'description' => 'Free entrepreneurship courses', 'tags' => ['khan-academy', 'entrepreneurship', 'free']],

            // Finance
            ['title' => 'Investopedia', 'url' => 'https://investopedia.com', 'description' => 'Financial education and investing', 'tags' => ['finance', 'investing', 'education']],
            ['title' => 'Morningstar', 'url' => 'https://morningstar.com', 'description' => 'Investment research and analysis', 'tags' => ['investing', 'research', 'analysis']],
            ['title' => 'Yahoo Finance', 'url' => 'https://finance.yahoo.com', 'description' => 'Financial news and market data', 'tags' => ['finance', 'news', 'market-data']],

            // Marketing
            ['title' => 'HubSpot Academy', 'url' => 'https://academy.hubspot.com', 'description' => 'Free marketing and sales courses', 'tags' => ['hubspot', 'marketing', 'sales']],
            ['title' => 'Google Digital Marketing', 'url' => 'https://learndigital.withgoogle.com', 'description' => 'Free digital marketing courses', 'tags' => ['google', 'digital-marketing', 'free']],

            // Productivity & Management
            ['title' => 'McKinsey Insights', 'url' => 'https://mckinsey.com/insights', 'description' => 'Business strategy and management insights', 'tags' => ['mckinsey', 'strategy', 'management']],
            ['title' => 'MIT Sloan Management Review', 'url' => 'https://sloanreview.mit.edu', 'description' => 'Management research and insights', 'tags' => ['mit', 'management', 'research']],
        ];

        $this->createBookmarksForCategory($user, $categories['Business & Finance'], $bookmarks);
    }

    private function createProductivityBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Productivity Tools
            ['title' => 'Notion', 'url' => 'https://notion.so', 'description' => 'All-in-one workspace for notes and projects', 'tags' => ['notion', 'productivity', 'workspace']],
            ['title' => 'Todoist', 'url' => 'https://todoist.com', 'description' => 'Task management and to-do lists', 'tags' => ['todoist', 'tasks', 'todo']],
            ['title' => 'Trello', 'url' => 'https://trello.com', 'description' => 'Visual project management with boards', 'tags' => ['trello', 'project-management', 'boards']],
            ['title' => 'Asana', 'url' => 'https://asana.com', 'description' => 'Team collaboration and project tracking', 'tags' => ['asana', 'collaboration', 'project-tracking']],

            // Note-taking
            ['title' => 'Obsidian', 'url' => 'https://obsidian.md', 'description' => 'Knowledge management with linked notes', 'tags' => ['obsidian', 'notes', 'knowledge-management']],
            ['title' => 'Roam Research', 'url' => 'https://roamresearch.com', 'description' => 'Networked thought and research tool', 'tags' => ['roam', 'research', 'networked-thought']],
            ['title' => 'Evernote', 'url' => 'https://evernote.com', 'description' => 'Note-taking and organization app', 'tags' => ['evernote', 'notes', 'organization']],

            // Time Management
            ['title' => 'RescueTime', 'url' => 'https://rescuetime.com', 'description' => 'Time tracking and productivity analytics', 'tags' => ['rescuetime', 'time-tracking', 'analytics']],
            ['title' => 'Forest', 'url' => 'https://forestapp.cc', 'description' => 'Focus app with gamification', 'tags' => ['forest', 'focus', 'gamification']],
            ['title' => 'Toggl', 'url' => 'https://toggl.com', 'description' => 'Time tracking for projects', 'tags' => ['toggl', 'time-tracking', 'projects']],
        ];

        $this->createBookmarksForCategory($user, $categories['Productivity'], $bookmarks);
    }

    private function createNewsEducationBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // Educational News
            ['title' => 'TED Talks', 'url' => 'https://ted.com/talks', 'description' => 'Ideas worth spreading', 'tags' => ['ted', 'talks', 'ideas']],
            ['title' => 'BBC Learning', 'url' => 'https://bbc.co.uk/learning', 'description' => 'BBC\'s educational content', 'tags' => ['bbc', 'learning', 'education']],
            ['title' => 'National Geographic', 'url' => 'https://nationalgeographic.com', 'description' => 'Science, nature, and culture content', 'tags' => ['nat-geo', 'science', 'nature']],
            ['title' => 'Smithsonian Magazine', 'url' => 'https://smithsonianmag.com', 'description' => 'History, science, and culture articles', 'tags' => ['smithsonian', 'history', 'culture']],

            // Tech News
            ['title' => 'Hacker News', 'url' => 'https://news.ycombinator.com', 'description' => 'Tech news and startup community', 'tags' => ['hacker-news', 'tech', 'startups']],
            ['title' => 'TechCrunch', 'url' => 'https://techcrunch.com', 'description' => 'Technology news and analysis', 'tags' => ['techcrunch', 'technology', 'news']],
            ['title' => 'Ars Technica', 'url' => 'https://arstechnica.com', 'description' => 'In-depth technology journalism', 'tags' => ['ars-technica', 'technology', 'journalism']],

            // Science News
            ['title' => 'Science Daily', 'url' => 'https://sciencedaily.com', 'description' => 'Latest science news and research', 'tags' => ['science-daily', 'science', 'research']],
            ['title' => 'New Scientist', 'url' => 'https://newscientist.com', 'description' => 'Science news and discoveries', 'tags' => ['new-scientist', 'science', 'discoveries']],
            ['title' => 'Scientific American', 'url' => 'https://scientificamerican.com', 'description' => 'Science journalism and analysis', 'tags' => ['scientific-american', 'science', 'journalism']],
        ];

        $this->createBookmarksForCategory($user, $categories['News & Education'], $bookmarks);
    }

    private function createDocumentationBookmarks(User $user, array $categories): void
    {
        $bookmarks = [
            // General Documentation
            ['title' => 'DevDocs', 'url' => 'https://devdocs.io', 'description' => 'Unified documentation for developers', 'tags' => ['devdocs', 'documentation', 'unified']],
            ['title' => 'Stack Overflow', 'url' => 'https://stackoverflow.com', 'description' => 'Programming Q&A community', 'tags' => ['stackoverflow', 'programming', 'qa']],
            ['title' => 'GitHub', 'url' => 'https://github.com', 'description' => 'Code hosting and collaboration platform', 'tags' => ['github', 'code', 'collaboration']],

            // Technology Documentation
            ['title' => 'Docker Documentation', 'url' => 'https://docs.docker.com', 'description' => 'Container platform documentation', 'tags' => ['docker', 'containers', 'documentation']],
            ['title' => 'Kubernetes Documentation', 'url' => 'https://kubernetes.io/docs/', 'description' => 'Container orchestration documentation', 'tags' => ['kubernetes', 'orchestration', 'documentation']],
            ['title' => 'AWS Documentation', 'url' => 'https://docs.aws.amazon.com', 'description' => 'Amazon Web Services documentation', 'tags' => ['aws', 'cloud', 'documentation']],

            // Development Resources
            ['title' => 'Regex101', 'url' => 'https://regex101.com', 'description' => 'Regular expression tester and debugger', 'tags' => ['regex', 'testing', 'debugging']],
            ['title' => 'JSON Formatter', 'url' => 'https://jsonformatter.org', 'description' => 'JSON validation and formatting tool', 'tags' => ['json', 'formatting', 'validation']],
            ['title' => 'Base64 Decode', 'url' => 'https://base64decode.org', 'description' => 'Base64 encoding and decoding tool', 'tags' => ['base64', 'encoding', 'decoding']],
        ];

        $this->createBookmarksForCategory($user, $categories['Documentation'], $bookmarks);
    }

    private function createBookmarksForCategory(User $user, Category $category, array $bookmarks): void
    {
        foreach ($bookmarks as $bookmarkData) {
            $bookmark = Bookmark::firstOrCreate([
                'user_id' => $user->id,
                'url' => $bookmarkData['url'],
            ], [
                'title' => $bookmarkData['title'],
                'description' => $bookmarkData['description'],
                'category_id' => $category->id,
                'visits' => rand(0, 50),
                'favorite' => rand(0, 10) > 8, // 20% chance of being favorite
                'private' => rand(0, 10) > 7,  // 30% chance of being private
                'status' => 'active',
            ]);

            // Add tags
            if (isset($bookmarkData['tags'])) {
                $tagIds = [];
                foreach ($bookmarkData['tags'] as $tagName) {
                    $tag = Tag::firstOrCreate([
                        'slug' => Str::slug($tagName),
                    ], [
                        'name' => $tagName,
                    ]);
                    $tagIds[] = $tag->id;
                }
                $bookmark->tags()->sync($tagIds);
            }
        }
    }
}
