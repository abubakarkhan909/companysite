<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$categories = $pdo->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
$tags = $pdo->query("SELECT * FROM tags ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $userId = $_SESSION['user_id'];
    $category_id = $_POST['category_id'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    // Generate unique slug
    $originalSlug = $slug;
    $i = 1;
    while (true) {
        $check = $pdo->prepare("SELECT COUNT(*) FROM blogs WHERE slug = ?");
        $check->execute([$slug]);
        if ($check->fetchColumn() == 0) break;
        $slug = $originalSlug . '-' . $i++;
    }

    // Upload picture
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['picture']['type'], $allowedTypes)) {
            die('Invalid image format. Allowed formats: JPG, PNG, WEBP.');
        }
    $picturePath = null;
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['picture']['name']);
        $fileExt = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('blog_', true) . '.' . $fileExt;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPath)) {
            $picturePath = $targetPath;
        }
    }

    // Insert blog
    $stmt = $pdo->prepare("INSERT INTO blogs (title, slug, content, user_id, category_id, picture) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $content, $userId, $category_id, $picturePath]);

    $blog_id = $pdo->lastInsertId();

    if (!empty($_POST['tags'])) {
        $stmt = $pdo->prepare("INSERT INTO blog_tag (blog_id, tag_id) VALUES (?, ?)");
        foreach ($_POST['tags'] as $tag_id) {
            $stmt->execute([$blog_id, $tag_id]);
        }
    }

    header("Location: ./dashboard.php");
    exit;
}
include 'header.php';

?>

<div class="card shadow-sm p-4 mx-auto">
  <h4 class="mb-3">Create New Blog</h4>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <input type="text" name="title" class="form-control" placeholder="Title" required>
    </div>
    <div class="mb-3">
    <label for="category_id" class="form-label">Featured Image</label>

      <input type="file" name="picture" class="form-control" required>
    </div>
    <div class="mb-3">
      <textarea name="content" id="editor" class="form-control" rows="6"></textarea>
    </div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Category</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>">
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label>Select Tags</label>
        <?php foreach ($tags as $tag): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="tags[]" value="<?= $tag['id'] ?>" id="tag<?= $tag['id'] ?>">
                <label class="form-check-label" for="tag<?= $tag['id'] ?>">
                    <?= htmlspecialchars($tag['name']) ?>
                </label>
            </div>
        <?php endforeach; ?>
    </div>

    <button type="submit" class="btn btn-success">Create Blog</button>
    <a href="./dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script src="./ckeditor/ckeditor.js"></script>
<script>
function MyUploadAdapter(loader) {
    this.loader = loader;
}

MyUploadAdapter.prototype.upload = function() {
    return new Promise((resolve, reject) => {
        // Check if the loader.file is a Promise (it shouldn't be, but let's handle it)
        const file = this.loader.file;

        // If it's a Promise, resolve it
        if (file instanceof Promise) {
            file.then(resolvedFile => {
                // Now resolvedFile contains the actual file object
                proceedWithUpload(resolvedFile);
            }).catch(error => {
                reject('Error resolving file: ' + error);
            });
        } else {
            // If it's already a file (not a Promise), proceed with upload
            proceedWithUpload(file);
        }

        function proceedWithUpload(file) {
            const data = new FormData();
            data.append('file', file);

            // Make the fetch request to upload.php
            fetch('upload.php', {
                method: 'POST',
                body: data
            })
            .then(response => response.json())
            .then(result => {
                if (result && result.url) {
                    resolve({
                        default: result.url
                    });
                } else {
                    reject('Upload failed');
                }
            })
            .catch(error => reject('Error during file upload: ' + error));
        }
    });
}

function MyUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
        return new MyUploadAdapter(loader);
    };
}

document.addEventListener('DOMContentLoaded', function () {
    ClassicEditor
        .create(document.querySelector('#editor'), {
            extraPlugins: [ MyUploadAdapterPlugin ],  // Add the custom upload adapter
        })
        .catch(error => console.error('CKEditor error: ', error));  // Handle CKEditor errors
});
// Ensure CKEditor content is passed to the textarea before form submission
document.querySelector('form').addEventListener('submit', function(event) {
    const editorContent = ClassicEditor.instances.editor.getData();
    if (!editorContent.trim()) {
        alert("Content cannot be empty!");
        event.preventDefault();  // Prevent form submission if content is empty
    } else {
        // Set CKEditor content to the hidden textarea before submitting the form
        document.querySelector('textarea[name="content"]').value = editorContent;
    }
});
</script>

<?php include 'footer.php'; ?>
