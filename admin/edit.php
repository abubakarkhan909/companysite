<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./login.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$blog = $stmt->fetch();
$categories = $pdo->query("SELECT * FROM blog_categories ORDER BY name")->fetchAll();
$tags = $pdo->query("SELECT * FROM tags ORDER BY name")->fetchAll();

$selectedTags = $pdo->prepare("SELECT tag_id FROM blog_tag WHERE blog_id = ?");
$selectedTags->execute([$id]); // $id = blog ID you're editing
$selectedTagIds = $selectedTags->fetchAll(PDO::FETCH_COLUMN);

if (!$blog) {
    echo "<div class='alert alert-danger'>Blog not found.</div>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPicturePath = $blog['picture'];

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['picture']['type'], $allowedTypes)) {
            die('Invalid image format. Allowed formats: JPG, PNG, WEBP.');
        }

        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['picture']['name']);
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('blog_', true) . '.' . $ext;
        $targetPath = $uploadDir . $newFileName;

        if (move_uploaded_file($_FILES['picture']['tmp_name'], $targetPath)) {
            // Remove old image if it exists
            if (!empty($blog['picture']) && file_exists($blog['picture'])) {
                unlink($blog['picture']);
            }

            $newPicturePath = $targetPath;
        }
    }

    // Update blog record
    $stmt = $pdo->prepare("UPDATE blogs SET title = ?, content = ?, category_id = ?, picture = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$_POST['title'], $_POST['content'], $_POST['category_id'], $newPicturePath, $id, $_SESSION['user_id']]);

    // Update tags
    $pdo->prepare("DELETE FROM blog_tag WHERE blog_id = ?")->execute([$id]);
    if (!empty($_POST['tags'])) {
        $stmt = $pdo->prepare("INSERT INTO blog_tag (blog_id, tag_id) VALUES (?, ?)");
        foreach ($_POST['tags'] as $tag_id) {
            $stmt->execute([$id, $tag_id]);
        }
    }

    header("Location: ./dashboard.php");
    exit;
}
include 'header.php';

?>

<div class="card shadow-sm p-4 mx-auto">
  <h4 class="mb-3">Edit Blog</h4>
  <form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($blog['title']) ?>" required>
    </div>
    <?php if (!empty($blog['picture']) && file_exists($blog['picture'])): ?>
        <div class="mb-3">
            <label class="form-label">Current Image:</label><br>
            <img src="./<?= $blog['picture'] ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
        </div>
    <?php endif; ?>
    <div class="mb-3">
        <label class="form-label">Change Image</label>
        <input type="file" name="picture" class="form-control">
    </div>

    <div class="mb-3">
      <!-- Make the textarea for CKEditor visible but keep it hidden in the form -->
      <textarea name="content" id="editor" class="form-control" rows="6" style="visibility: hidden; height: 1px;" required><?= htmlspecialchars($blog['content']) ?></textarea>
    </div>
    <div class="mb-3">
    <label for="category_id" class="form-label">Category</label>
    <select name="category_id" class="form-select" required>
        <option value="">-- Select Category --</option>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= ($blog['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($cat['name']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <div class="mb-3">
    <label class="form-label">Select Tags</label>
    <?php foreach ($tags as $tag): ?>
        <div class="form-check">
            <input 
                class="form-check-input" 
                type="checkbox" 
                name="tags[]" 
                value="<?= $tag['id'] ?>" 
                id="tag<?= $tag['id'] ?>"
                <?= in_array($tag['id'], $selectedTagIds) ? 'checked' : '' ?>
            >
            <label class="form-check-label" for="tag<?= $tag['id'] ?>">
                <?= htmlspecialchars($tag['name']) ?>
            </label>
        </div>
    <?php endforeach; ?>
</div>


</div>
    <button type="submit" class="btn btn-primary">Update Blog</button>
    <a href="./dashboard.php" class="btn btn-secondary">Cancel</a>
  </form>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
// Define custom upload adapter for CKEditor
function MyUploadAdapter(loader) {
    this.loader = loader;
}

MyUploadAdapter.prototype.upload = function() {
    return new Promise((resolve, reject) => {
        const file = this.loader.file;

        if (file instanceof Promise) {
            file.then(resolvedFile => {
                proceedWithUpload(resolvedFile);
            }).catch(error => {
                reject('Error resolving file: ' + error);
            });
        } else {
            proceedWithUpload(file);
        }

        function proceedWithUpload(file) {
            const data = new FormData();
            data.append('file', file);

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
            extraPlugins: [ MyUploadAdapterPlugin ],
        })
        .catch(error => console.error('CKEditor error: ', error));

    // Add manual form validation
    document.querySelector('form').addEventListener('submit', function(event) {
        const editorContent = ClassicEditor.instances.editor.getData();

        // Transfer CKEditor content to the hidden textarea
        document.querySelector('textarea[name="content"]').value = editorContent;

        // Check if content is empty before submitting
        if (!editorContent.trim()) {
            alert("Content cannot be empty!");
            event.preventDefault(); // Prevent form submission if content is empty
        }
    });
});
</script>

<?php include 'footer.php'; ?>
