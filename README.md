# monde - WordPress Project Gallery Plugin

![Version](https://img.shields.io/badge/version-3.1-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-5.0%2B-brightgreen.svg)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)

A comprehensive WordPress plugin designed for managing project portfolios with advanced filtering, category organization, and detailed project views. Perfect for agencies, freelancers, and businesses showcasing their work.

## 🎯 Features

### Core Functionality
- **Custom Post Type**: Dedicated "Projects" post type with full WordPress integration
- **Category Management**: Hierarchical taxonomy for organizing projects
- **Project Gallery**: Multi-image galleries with drag-and-drop management
- **Project Timeline**: Phase-based project timeline with descriptions
- **Advanced Filtering**: Front-end category filtering with smooth animations
- **Responsive Design**: Mobile-first approach with adaptive layouts

### Project Details
Each project supports comprehensive metadata:
- Client name
- Project URL
- Technologies used
- Completion date
- Budget (optional)
- Custom slug for SEO-friendly URLs
- Multiple project phases/milestones
- Image gallery with unlimited photos

### Admin Interface
- **Intuitive Meta Boxes**: Clean, organized project editing interface
- **Visual Gallery Manager**: Upload and arrange images with previews
- **Phase Builder**: Add multiple project phases with titles and descriptions
- **Category System**: Easy project categorization with taxonomy support

## 📦 Installation

### Method 1: WordPress Admin
1. Download the plugin ZIP file
2. Navigate to **Plugins > Add New** in WordPress admin
3. Click **Upload Plugin** and select the ZIP file
4. Click **Install Now** and then **Activate**

### Method 2: Manual Installation
1. Download and extract the plugin files
2. Upload the `monde-project-gallery` folder to `/wp-content/plugins/`
3. Navigate to **Plugins** in WordPress admin
4. Activate **Monde - Project Gallery Plugin**

### Method 3: FTP Upload
1. Extract the plugin ZIP file
2. Connect to your server via FTP
3. Upload the plugin folder to `/wp-content/plugins/`
4. Activate through WordPress admin panel

## 🚀 Quick Start

### Creating Your First Project

1. **Navigate to Projects**
   - Go to **Projects > Add New** in WordPress admin
   - Enter project title and description

2. **Add Project Details**
   - Fill in the "Project Details" meta box:
     - Client name
     - Project URL
     - Technologies (comma-separated)
     - Completion date
     - Budget (optional)
     - Custom slug

3. **Upload Gallery Images**
   - Click "Add photos to gallery" button
   - Select multiple images from Media Library
   - Arrange order by dragging thumbnails
   - Remove unwanted images with the × button

4. **Add Project Phases**
   - Click "+ Add phase" button
   - Enter phase title and description
   - Add multiple phases to create a timeline
   - Remove phases as needed

5. **Set Category**
   - Assign project to one or more categories
   - Categories can be created in **Projects > Categories**

6. **Publish**
   - Set featured image (recommended)
   - Click **Publish** to make project live

## 📝 Usage

### Displaying Projects with Shortcode

Use the `[monde_gallery]` shortcode to display projects anywhere on your site:

```php
[monde_gallery]
```

#### Shortcode Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `per_page` | integer | 12 | Number of projects to display |
| `category` | string | '' | Filter by category slug |
| `order` | string | DESC | Sort order (ASC/DESC) |
| `orderby` | string | date | Sort by (date, title, etc.) |

#### Examples

**Display all projects:**
```php
[monde_gallery]
```

**Show 6 projects per page:**
```php
[monde_gallery per_page="6"]
```

**Filter by category:**
```php
[monde_gallery category="web-development"]
```

**Custom sorting:**
```php
[monde_gallery orderby="title" order="ASC"]
```

**Combine parameters:**
```php
[monde_gallery per_page="9" category="design" orderby="date" order="DESC"]
```

### Using in Template Files

```php
<?php
// Get all projects
if (function_exists('monde_get_all_projects')) {
    $projects = monde_get_all_projects();
    
    foreach ($projects as $project) {
        echo '<h3>' . esc_html($project['title']) . '</h3>';
        echo '<p>' . esc_html($project['description']) . '</p>';
        echo '<img src="' . esc_url($project['image']) . '" alt="' . esc_attr($project['title']) . '">';
    }
}
?>
```

### Custom Query Example

```php
<?php
$args = array(
    'post_type' => 'monde_project',
    'posts_per_page' => 10,
    'tax_query' => array(
        array(
            'taxonomy' => 'monde_project_category',
            'field' => 'slug',
            'terms' => 'web-design',
        ),
    ),
);

$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        // Display project
    }
    wp_reset_postdata();
}
?>
```

## 🎨 Customization

### Styling

The plugin includes built-in styles that can be customized by adding CSS to your theme:

```css
/* Customize filter buttons */
.monde-filter-btn {
    border-color: #your-color;
    color: #your-color;
}

.monde-filter-btn.active {
    background: #your-color;
}

/* Customize project cards */
.monde-gallery-item {
    border-radius: 20px;
}

.monde-gallery-item:hover {
    transform: translateY(-15px);
}

/* Customize category badge */
.monde-gallery-category {
    background: linear-gradient(135deg, #your-color 0%, #your-darker-color 100%);
}
```

### Changing Colors

Override the default green color scheme:

```css
:root {
    --monde-primary: #your-color;
    --monde-primary-dark: #your-darker-color;
}
```

### Grid Layout

Modify the grid columns:

```css
.monde-gallery-grid {
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 30px;
}
```

## 🔧 Functions Reference

### `monde_get_all_projects()`

Returns an array of all projects with complete metadata.

**Returns:** Array of project objects

**Example:**
```php
$projects = monde_get_all_projects();
foreach ($projects as $project) {
    // Access project data
    $title = $project['title'];
    $image = $project['image'];
    $gallery = $project['gallery'];
}
```

**Project Object Structure:**
```php
array(
    'id' => int,
    'title' => string,
    'category' => string,
    'description' => string,
    'image' => string (URL),
    'gallery' => array (URLs),
    'project_url' => string,
    'slug' => string,
    'client' => string,
    'technologies' => string,
    'completion_date' => string,
    'budget' => string,
    'phases' => array,
    'subtitle' => string,
    'image_alt' => string,
    'reverse' => boolean
)
```

## 📱 Mobile Responsiveness

The plugin is fully responsive with breakpoints at:
- **Desktop**: 1200px+ (3 columns)
- **Tablet**: 768px-1199px (2 columns)
- **Mobile**: 0-767px (1 column)

Mobile-specific features:
- Touch-optimized filtering
- Horizontal scroll for filter buttons
- Optimized image loading
- Responsive typography

## 🌐 Integration with Mobile Templates

### Working with Mobile App Views

This plugin is designed to work seamlessly with custom mobile templates like `realizacjeapp.php`:

```php
<?php
// In your mobile template
$projects = array();
if (function_exists('monde_get_all_projects')) {
    $projects = monde_get_all_projects();
}

foreach ($projects as $project) {
    // Display in mobile-optimized format
}
?>
```

### URL Structure

Projects support both ID and slug-based URLs:
- By ID: `projektapp.php?id=123`
- By slug: `projektapp.php?slug=modern-website-redesign`

## 🔌 Hooks & Filters

### Available Actions

```php
// After project post type registration
do_action('monde_after_register_post_type');

// Before saving project meta
do_action('monde_before_save_meta', $post_id);

// After saving project meta
do_action('monde_after_save_meta', $post_id);
```

### Available Filters

```php
// Modify project query arguments
$args = apply_filters('monde_project_query_args', $args);

// Modify project data output
$project = apply_filters('monde_project_data', $project);

// Modify gallery image size
$size = apply_filters('monde_gallery_image_size', 'large');
```

## 📊 Database Schema

### Post Type
- **Type**: `monde_project`
- **Slug**: `realizacje`
- **Supports**: title, editor, thumbnail, excerpt, custom-fields

### Taxonomy
- **Name**: `monde_project_category`
- **Slug**: `kategoria-projektu`
- **Hierarchical**: Yes

### Custom Fields (Post Meta)
- `_monde_client_name` - Client name
- `_monde_project_url` - Project URL
- `_monde_technologies` - Technologies used
- `_monde_completion_date` - Completion date
- `_monde_budget` - Project budget
- `_monde_project_slug` - Custom slug
- `_monde_project_gallery` - Gallery image IDs (comma-separated)
- `_monde_project_phases` - Array of project phases

## 🛠️ Troubleshooting

### Gallery Images Not Showing
1. Check if images are properly uploaded to Media Library
2. Verify gallery IDs are saved in post meta
3. Ensure proper image permissions
4. Check if images exist in the media library

### Filtering Not Working
1. Clear browser cache
2. Check JavaScript console for errors
3. Verify jQuery is loaded
4. Ensure no JavaScript conflicts with other plugins

### Permalink Issues
1. Go to **Settings > Permalinks**
2. Click **Save Changes** to flush rewrite rules
3. Test project URLs again

### Shortcode Not Displaying
1. Verify plugin is activated
2. Check shortcode syntax
3. Ensure you're using the correct shortcode name: `[monde_gallery]`
4. Test in a default WordPress theme

## 🔄 Updates & Maintenance

### Updating the Plugin
1. Backup your site before updating
2. Deactivate the plugin
3. Upload new plugin files
4. Reactivate the plugin
5. Visit **Settings > Permalinks** and save

### Data Preservation
All project data is stored in WordPress database and will be preserved during updates.

## Contributing

Contributions are welcome! Please feel free to submit pull requests or open issues on GitHub.

### Development Setup
1. Clone the repository
2. Install WordPress in a local environment
3. Activate the plugin
4. Make changes and test thoroughly

## 📄 License

This plugin is licensed under the GPL v2 or later.

## 👨‍💻 Author

**sneanyen**
- GitHub: [@sneanyen](https://github.com/sneanyen)

## 🙏 Credits

- WordPress Core APIs
- WordPress Media Library
- Custom Post Types
- Taxonomies

## 📞 Support

For support, feature requests, or bug reports:
1. Open an issue on GitHub
2. Visit the plugin support forum
3. Check documentation for common issues

---

**Version**: 3.1  
**Requires WordPress**: 5.0 or higher  
**Requires PHP**: 7.4 or higher  
**Last Updated**: 2025
