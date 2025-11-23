<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
$page_title = 'News & Updates';
require_once 'includes/header.php';

// Get database connection
$conn = getDBConnection();

// Fetch all published news articles
$news = [];
$sql = "SELECT * FROM news WHERE published = 1 ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $news[] = $row;
    }
}

$conn->close();
?>

<!-- Page Header -->
<section class="page-header">
    <div class="container">
        <h1>News & Updates</h1>
        <p class="lead">Stay informed about our latest activities and initiatives</p>
    </div>
</section>

<!-- News Introduction -->
<section class="news-intro">
    <div class="container">
        <div class="intro-content">
            <h2>Latest Updates</h2>
            <p class="large-text">
                Keep up to date with the latest news, events, and success stories from 
                Inclusive Rwanda. Learn about our ongoing projects, partnerships, and 
                the positive impact we're making across the country.
            </p>
        </div>
    </div>
</section>

<!-- News Articles -->
<section class="news-articles">
    <div class="container">
        <?php if (count($news) > 0): ?>
            <div class="news-grid">
                <?php foreach ($news as $article): ?>
                    <article class="news-card" aria-labelledby="news-<?php echo $article['id']; ?>">
                        <?php if (!empty($article['image'])): ?>
                            <div class="news-image">
                                <img 
                                    src="<?php echo BASE_URL . 'admin/uploads/' . htmlspecialchars($article['image']); ?>" 
                                    alt="<?php echo htmlspecialchars($article['title']); ?>"
                                    loading="lazy"
                                >
                            </div>
                        <?php else: ?>
                            <div class="news-image-placeholder" aria-hidden="true">
                                📰
                            </div>
                        <?php endif; ?>
                        
                        <div class="news-content">
                            <div class="news-meta">
                                <span class="news-date">
                                    <time datetime="<?php echo date('Y-m-d', strtotime($article['created_at'])); ?>">
                                        <?php echo date('F j, Y', strtotime($article['created_at'])); ?>
                                    </time>
                                </span>
                                <span class="news-author">
                                    By <?php echo htmlspecialchars($article['author']); ?>
                                </span>
                            </div>
                            
                            <h3 id="news-<?php echo $article['id']; ?>">
                                <?php echo htmlspecialchars($article['title']); ?>
                            </h3>
                            
                            <div class="news-excerpt">
                                <?php 
                                    // Display first 200 characters of content
                                    $excerpt = substr($article['content'], 0, 200);
                                    // Find the last space to avoid cutting words
                                    $lastSpace = strrpos($excerpt, ' ');
                                    if ($lastSpace !== false) {
                                        $excerpt = substr($excerpt, 0, $lastSpace);
                                    }
                                    echo htmlspecialchars($excerpt) . '...';
                                ?>
                            </div>
                            
                            <button 
                                class="btn-read-more" 
                                onclick="showFullArticle(<?php echo $article['id']; ?>)"
                                aria-label="Read full article: <?php echo htmlspecialchars($article['title']); ?>"
                            >
                                Read More →
                            </button>
                        </div>
                        
                        <!-- Hidden full content for modal -->
                        <div id="full-content-<?php echo $article['id']; ?>" style="display: none;">
                            <?php echo nl2br(htmlspecialchars($article['content'])); ?>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-content">
                <p>No news articles available at the moment. Please check back soon!</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Newsletter Signup -->
<section class="newsletter-signup">
    <div class="container">
        <div class="newsletter-content">
            <h2>Stay Connected</h2>
            <p>Subscribe to our newsletter to receive updates directly in your inbox</p>
            <form class="newsletter-form" onsubmit="return false;">
                <input 
                    type="email" 
                    placeholder="Enter your email address" 
                    required
                    aria-label="Email address for newsletter"
                >
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
            <small>We respect your privacy and will never share your information.</small>
        </div>
    </div>
</section>

<!-- Modal for full article -->
<div id="article-modal" class="modal" role="dialog" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal()" aria-label="Close modal">×</button>
        <h2 id="modal-title"></h2>
        <div class="modal-meta"></div>
        <div class="modal-body"></div>
    </div>
</div>

<script>
// Function to show full article in modal
function showFullArticle(articleId) {
    const modal = document.getElementById('article-modal');
    const card = document.querySelector(`#news-${articleId}`).closest('.news-card');
    
    // Get article data
    const title = card.querySelector('h3').textContent;
    const meta = card.querySelector('.news-meta').innerHTML;
    const content = document.getElementById(`full-content-${articleId}`).innerHTML;
    
    // Populate modal
    document.getElementById('modal-title').textContent = title;
    document.querySelector('.modal-meta').innerHTML = meta;
    document.querySelector('.modal-body').innerHTML = content;
    
    // Show modal
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
}

// Function to close modal
function closeModal() {
    const modal = document.getElementById('article-modal');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('article-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

<?php require_once 'includes/footer.php'; ?>