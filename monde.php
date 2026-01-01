<?php
/**
 * Plugin Name: monde
 * Plugin URI: github.com/sneanyen
 * Description: monde was designed for managing a comprehensive project gallery, featuring advanced filtering options, category-based organization, and detailed project views to help users easily browse, sort, and explore showcased work
 * Version: 3.1
 * Author: sneanyen
 * Author URI: github.com/sneanyen
 * Text Domain: monde-project-gallery
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MONDE_GALLERY_VERSION', '3.1');
define('MONDE_GALLERY_PATH', plugin_dir_path(__FILE__));
define('MONDE_GALLERY_URL', plugin_dir_url(__FILE__));

add_action('init', 'monde_register_project_post_type');

function monde_register_project_post_type() {
    $labels = array(
        'name'               => 'Projekty',
        'singular_name'      => 'Projekt',
        'menu_name'          => 'Projekty',
        'name_admin_bar'     => 'Projekt',
        'add_new'            => 'Dodaj nowy',
        'add_new_item'       => 'Dodaj nowy projekt',
        'new_item'           => 'Nowy projekt',
        'edit_item'          => 'Edytuj projekt',
        'view_item'          => 'Pokaż projekt',
        'all_items'          => 'Wszystkie projekty',
        'search_items'       => 'Szukaj projektów',
        'not_found'          => 'Nie znaleziono projektów',
        'not_found_in_trash' => 'Nie znaleziono projektów w koszu',
    );

    $args = array(
        'labels'             => $labels,
        'description'        => 'Custom post type dla galerii projektów',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'realizacje'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
    );

    register_post_type('monde_project', $args);
}

add_action('init', 'monde_register_project_category');

function monde_register_project_category() {
    $labels = array(
        'name'                       => 'Kategorie projektów',
        'singular_name'              => 'Kategoria projektu',
        'search_items'               => 'Szukaj kategorii',
        'all_items'                  => 'Wszystkie kategorie',
        'parent_item'                => 'Kategoria nadrzędna',
        'parent_item_colon'          => 'Kategoria nadrzędna:',
        'edit_item'                  => 'Edytuj kategorię',
        'update_item'                => 'Aktualizuj kategorię',
        'add_new_item'               => 'Dodaj nową kategorię',
        'new_item_name'              => 'Nazwa nowej kategorii',
        'menu_name'                  => 'Kategorie',
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'kategoria-projektu'),
    );

    register_taxonomy('monde_project_category', array('monde_project'), $args);
}

add_action('add_meta_boxes', 'monde_add_project_meta_boxes');

function monde_add_project_meta_boxes() {
    add_meta_box(
        'monde_project_details',
        'Szczegóły projektu',
        'monde_project_details_callback',
        'monde_project',
        'normal',
        'high'
    );
    
    add_meta_box(
        'monde_project_gallery',
        'Galeria zdjęć',
        'monde_project_gallery_callback',
        'monde_project',
        'normal',
        'high'
    );
    
    add_meta_box(
        'monde_project_phases',
        'Fazy projektu (Timeline)',
        'monde_project_phases_callback',
        'monde_project',
        'normal',
        'high'
    );
}

function monde_project_details_callback($post) {
    wp_nonce_field('monde_project_nonce', 'monde_project_nonce');
    
    $client = get_post_meta($post->ID, '_monde_client_name', true);
    $project_url = get_post_meta($post->ID, '_monde_project_url', true);
    $technologies = get_post_meta($post->ID, '_monde_technologies', true);
    $completion_date = get_post_meta($post->ID, '_monde_completion_date', true);
    $budget = get_post_meta($post->ID, '_monde_budget', true);
    $project_slug = get_post_meta($post->ID, '_monde_project_slug', true);
    
    ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <label for="monde_client_name"><strong>Nazwa klienta</strong></label>
            <input type="text" id="monde_client_name" name="monde_client_name" value="<?php echo esc_attr($client); ?>" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        
        <div>
            <label for="monde_completion_date"><strong>Data ukończenia</strong></label>
            <input type="date" id="monde_completion_date" name="monde_completion_date" value="<?php echo esc_attr($completion_date); ?>" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        
        <div>
            <label for="monde_project_url"><strong>Link do projektu (URL)</strong></label>
            <input type="url" id="monde_project_url" name="monde_project_url" value="<?php echo esc_attr($project_url); ?>" placeholder="https://..." style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        
        <div>
            <label for="monde_budget"><strong>Budżet (opcjonalnie)</strong></label>
            <input type="text" id="monde_budget" name="monde_budget" value="<?php echo esc_attr($budget); ?>" placeholder="np. 5000 PLN" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
        
        <div style="grid-column: 1 / -1;">
            <label for="monde_project_slug"><strong>Slug projektu (dla linkowania - np: nowoczesny-loft)</strong></label>
            <input type="text" id="monde_project_slug" name="monde_project_slug" value="<?php echo esc_attr($project_slug); ?>" placeholder="nowoczesny-loft" style="width: 100%; padding: 8px; margin-top: 5px;">
        </div>
    </div>
    
    <div style="margin-top: 20px;">
        <label for="monde_technologies"><strong>Użyte technologie (oddzielone przecinkami)</strong></label>
        <textarea id="monde_technologies" name="monde_technologies" style="width: 100%; padding: 8px; margin-top: 5px; min-height: 80px;"><?php echo esc_textarea($technologies); ?></textarea>
    </div>
    <?php
}

function monde_project_gallery_callback($post) {
    wp_nonce_field('monde_gallery_nonce', 'monde_gallery_nonce');
    
    $gallery_ids = get_post_meta($post->ID, '_monde_project_gallery', true);
    $gallery_ids = is_array($gallery_ids) ? $gallery_ids : explode(',', $gallery_ids);
    $gallery_ids = array_filter(array_map('intval', $gallery_ids));
    
    ?>
    <div style="background: #69b53f; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        <p style="margin-top: 0;"><strong>Zarządzaj galerią zdjęć projektu</strong></p>
        
        <div id="monde-gallery-preview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 10px; margin-bottom: 15px;">
            <?php foreach ($gallery_ids as $attachment_id): ?>
                <div class="monde-gallery-thumbnail" data-attachment-id="<?php echo $attachment_id; ?>" style="position: relative; border-radius: 6px; overflow: hidden; border: 2px solid #ddd;">
                    <?php echo wp_get_attachment_image($attachment_id, 'medium', false, array('style' => 'width: 100%; height: 120px; object-fit: cover;')); ?>
                    <button type="button" class="monde-remove-image" data-attachment-id="<?php echo $attachment_id; ?>" style="position: absolute; top: 2px; right: 2px; background: #d90000; color: white; border: none; border-radius: 3px; padding: 4px 8px; cursor: pointer; font-size: 12px; font-weight: 600;">✕</button>
                </div>
            <?php endforeach; ?>
        </div>
        
        <input type="hidden" id="monde_project_gallery" name="monde_project_gallery" value="<?php echo esc_attr(implode(',', $gallery_ids)); ?>">
        
        <button type="button" id="monde-upload-gallery" style="padding: 10px 20px; background: #69b53f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 14px;">+ Dodaj zdjęcia do galerii</button>
    </div>
    
    <script>
        (function() {
            const uploadBtn = document.getElementById('monde-upload-gallery');
            
            if (!uploadBtn) return;
            
            uploadBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (typeof wp === 'undefined' || !wp.media) {
                    alert('WordPress Media Library nie jest dostępna');
                    return;
                }
                
                const frame = wp.media({
                    title: 'Wybierz zdjęcia do galerii',
                    button: {
                        text: 'Dodaj do galerii'
                    },
                    multiple: true,
                    library: {
                        type: 'image'
                    }
                });
                
                frame.on('select', function() {
                    const selection = frame.state().get('selection');
                    const currentIds = document.getElementById('monde_project_gallery').value.split(',').filter(Boolean).map(Number);
                    const newIds = [];
                    
                    selection.each(function(attachment) {
                        const id = attachment.id;
                        if (!currentIds.includes(id) && !newIds.includes(id)) {
                            newIds.push(id);
                        }
                    });
                    
                    const allIds = currentIds.concat(newIds);
                    updateGallery(allIds);
                });
                
                frame.open();
            });
            
            function updateGallery(ids) {
                const inputField = document.getElementById('monde_project_gallery');
                inputField.value = ids.join(',');
                
                const preview = document.getElementById('monde-gallery-preview');
                preview.innerHTML = '';
                
                ids.forEach(function(id) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'monde-gallery-thumbnail';
                    wrapper.setAttribute('data-attachment-id', id);
                    wrapper.style.cssText = 'position: relative; border-radius: 6px; overflow: hidden; border: 2px solid #ddd;';
                    
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'monde-remove-image';
                    removeBtn.setAttribute('data-attachment-id', id);
                    removeBtn.style.cssText = 'position: absolute; top: 2px; right: 2px; background: #d90000; color: white; border: none; border-radius: 3px; padding: 4px 8px; cursor: pointer; font-size: 12px; font-weight: 600; z-index: 10;';
                    removeBtn.textContent = '✕';

                    wp.media.attachment(id).fetch().done(function() {
                        const img = new Image();
                        img.src = wp.media.attachment(id).get('url');
                        img.style.cssText = 'width: 100%; height: 120px; object-fit: cover; display: block;';
                        wrapper.appendChild(img);
                        wrapper.appendChild(removeBtn);
                        preview.appendChild(wrapper);
                        attachRemoveListener(removeBtn);
                    });
                });
            }
            
            function attachRemoveListener(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.getAttribute('data-attachment-id');
                    const currentIds = document.getElementById('monde_project_gallery').value.split(',').filter(Boolean).map(Number);
                    const newIds = currentIds.filter(item => item !== parseInt(id));
                    updateGallery(newIds);
                });
            }

            document.querySelectorAll('.monde-remove-image').forEach(attachRemoveListener);
        })();
    </script>
    <?php
}

function monde_project_phases_callback($post) {
    $phases = get_post_meta($post->ID, '_monde_project_phases', true);
    $phases = is_array($phases) ? $phases : array();
    
    ?>
    <div id="monde-phases-container" style="display: flex; flex-direction: column; gap: 15px;">
        <?php foreach ($phases as $index => $phase): ?>
            <div class="monde-phase-item" style="background: #f5f5f5; padding: 15px; border-radius: 8px; border-left: 4px solid #69b53f;">
                <input type="text" name="monde_phase_title[]" value="<?php echo esc_attr($phase['title'] ?? ''); ?>" placeholder="Tytuł fazy" style="width: 100%; padding: 8px; margin-bottom: 10px;">
                <textarea name="monde_phase_description[]" placeholder="Opis fazy" style="width: 100%; padding: 8px; min-height: 60px;"><?php echo esc_textarea($phase['description'] ?? ''); ?></textarea>
                <button type="button" class="monde-remove-phase" style="margin-top: 8px; padding: 6px 12px; background: #ff6b6b; color: white; border: none; border-radius: 4px; cursor: pointer;">Usuń fazę</button>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="button" id="monde-add-phase" style="margin-top: 15px; padding: 10px 20px; background: #69b53f; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: 600;">+ Dodaj fazę</button>
    
    <script>
        document.getElementById('monde-add-phase').addEventListener('click', function() {
            const container = document.getElementById('monde-phases-container');
            const newPhase = document.createElement('div');
            newPhase.className = 'monde-phase-item';
            newPhase.style.cssText = 'background: #f5f5f5; padding: 15px; border-radius: 8px; border-left: 4px solid #69b53f;';
            newPhase.innerHTML = `
                <input type="text" name="monde_phase_title[]" placeholder="Tytuł fazy" style="width: 100%; padding: 8px; margin-bottom: 10px;">
                <textarea name="monde_phase_description[]" placeholder="Opis fazy" style="width: 100%; padding: 8px; min-height: 60px;"></textarea>
                <button type="button" class="monde-remove-phase" style="margin-top: 8px; padding: 6px 12px; background: #d90000; color: white; border: none; border-radius: 4px; cursor: pointer;">Usuń fazę</button>
            `;
            container.appendChild(newPhase);
            attachRemoveListener(newPhase.querySelector('.monde-remove-phase'));
        });
        
        function attachRemoveListener(btn) {
            btn.addEventListener('click', function() {
                this.closest('.monde-phase-item').remove();
            });
        }
        
        document.querySelectorAll('.monde-remove-phase').forEach(attachRemoveListener);
    </script>
    <?php
}

add_action('save_post_monde_project', 'monde_save_project_meta');

function monde_save_project_meta($post_id) {
    if (!isset($_POST['monde_project_nonce']) || !wp_verify_nonce($_POST['monde_project_nonce'], 'monde_project_nonce')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    $fields = array('monde_client_name', 'monde_project_url', 'monde_technologies', 'monde_completion_date', 'monde_budget', 'monde_project_slug');
    
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }

    if (isset($_POST['monde_project_gallery'])) {
        update_post_meta($post_id, '_monde_project_gallery', sanitize_text_field($_POST['monde_project_gallery']));
    }

    if (isset($_POST['monde_phase_title']) && is_array($_POST['monde_phase_title'])) {
        $phases = array();
        foreach ($_POST['monde_phase_title'] as $index => $title) {
            if (!empty($title)) {
                $phases[] = array(
                    'title' => sanitize_text_field($title),
                    'description' => sanitize_textarea_field($_POST['monde_phase_description'][$index] ?? ''),
                );
            }
        }
        update_post_meta($post_id, '_monde_project_phases', $phases);
    }
}

function monde_get_all_projects() {
    $args = array(
        'post_type' => 'monde_project',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    );
    
    $query = new WP_Query($args);
    $projects = array();
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $categories = wp_get_post_terms(get_the_ID(), 'monde_project_category');
            $category_name = !empty($categories) ? $categories[0]->name : 'Bez kategorii';

            $gallery_ids = get_post_meta(get_the_ID(), '_monde_project_gallery', true);
            $gallery_ids = is_array($gallery_ids) ? $gallery_ids : explode(',', $gallery_ids);
            $gallery_ids = array_filter(array_map('intval', $gallery_ids));
            
            $gallery_images = array();
            foreach ($gallery_ids as $attachment_id) {
                $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                if (!$image_url) {
                    $image_url = wp_get_attachment_url($attachment_id);
                }
                if ($image_url) {
                    $gallery_images[] = $image_url;
                }
            }

            $primary_image = get_the_post_thumbnail_url(get_the_ID(), 'full') ?: get_the_post_thumbnail_url(get_the_ID(), 'large');

            if (empty($primary_image)) {
                $attachments = get_attached_media('image', get_the_ID());
                if (!empty($attachments)) {
                    foreach ($attachments as $att) {
                        $url = wp_get_attachment_image_url($att->ID, 'large');
                        if (!$url) $url = wp_get_attachment_url($att->ID);
                        if ($url) {
                            $primary_image = $url;
                            break;
                        }
                    }
                }
            }

            if (empty($primary_image)) {
                $post = get_post(get_the_ID());
                if ($post && preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $post->post_content, $m)) {
                    $primary_image = $m[1];
                }
            }

            if (empty($primary_image)) {
                $primary_image = 'https://placehold.co/600x400/31a9ff/ffffff?text=' . urlencode(get_the_title());
            }

            if (empty($gallery_images)) {
                $gallery_images = array($primary_image);
            }
            
            $projects[] = array(
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'category' => $category_name,
                'description' => get_the_excerpt() ?: wp_trim_words(get_the_content(), 20),
                'image' => $gallery_images[0],
                'gallery' => $gallery_images,
                'project_url' => get_post_meta(get_the_ID(), '_monde_project_url', true),
                'slug' => get_post_meta(get_the_ID(), '_monde_project_slug', true),
                'client' => get_post_meta(get_the_ID(), '_monde_client_name', true),
                'technologies' => get_post_meta(get_the_ID(), '_monde_technologies', true),
                'completion_date' => get_post_meta(get_the_ID(), '_monde_completion_date', true),
                'budget' => get_post_meta(get_the_ID(), '_monde_budget', true),
                'phases' => get_post_meta(get_the_ID(), '_monde_project_phases', true) ?: array(),
                'subtitle' => get_the_excerpt(),
                'image_alt' => get_the_title(),
                'reverse' => (get_the_ID() % 2 === 0),
            );
        }
        wp_reset_postdata();
    }
    
    return $projects;
}

add_shortcode('monde_gallery', 'monde_gallery_shortcode');

function monde_gallery_shortcode($atts) {
    $atts = shortcode_atts(array(
        'per_page' => 12,
        'category' => '',
        'order' => 'DESC',
        'orderby' => 'date',
    ), $atts);

    ob_start();
    ?>
    <div class="monde-gallery-wrapper">
        <div class="monde-gallery-filters">
            <button class="monde-filter-btn active" data-filter="all">Wszystkie</button>
            <?php
            $categories = get_terms(array(
                'taxonomy' => 'monde_project_category',
                'hide_empty' => true,
            ));
            
            if (!empty($categories)) {
                foreach ($categories as $cat) {
                    echo '<button class="monde-filter-btn" data-filter="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</button>';
                }
            }
            ?>
        </div>

        <div class="monde-gallery-grid" id="monde-gallery-grid">
            <?php
            $args = array(
                'post_type' => 'monde_project',
                'posts_per_page' => intval($atts['per_page']),
                'orderby' => sanitize_text_field($atts['orderby']),
                'order' => sanitize_text_field($atts['order']),
            );

            if (!empty($atts['category'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'monde_project_category',
                        'field' => 'slug',
                        'terms' => sanitize_text_field($atts['category']),
                    ),
                );
            }

            $query = new WP_Query($args);

            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    $categories = wp_get_post_terms(get_the_ID(), 'monde_project_category');
                    $cat_class = !empty($categories) ? 'cat-' . $categories[0]->slug : '';

                    $gallery_ids = get_post_meta(get_the_ID(), '_monde_project_gallery', true);
                    $gallery_ids = is_array($gallery_ids) ? $gallery_ids : explode(',', $gallery_ids);
                    $gallery_ids = array_filter(array_map('intval', $gallery_ids));
                    
                    if (!empty($gallery_ids)) {
                        $thumbnail_url = wp_get_attachment_image_url($gallery_ids[0], 'large');
                    } else {
                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                    }
                    
                    if (empty($thumbnail_url)) {
                        continue;
                    }
                    
                    $project_slug = get_post_meta(get_the_ID(), '_monde_project_slug', true);
                    $project_id = get_the_ID();
                    
                    $link = $project_slug ? 'projektapp.php?slug=' . urlencode($project_slug) : 'projektapp.php?id=' . $project_id;
                    ?>
                    <div class="monde-gallery-item <?php echo esc_attr($cat_class); ?>" data-categories="<?php echo esc_attr($cat_class); ?>">
                        <div class="monde-gallery-item-image" style="background-image: url('<?php echo esc_url($thumbnail_url); ?>');">
                            <?php if (!empty($categories)): ?>
                                <span class="monde-gallery-category"><?php echo esc_html($categories[0]->name); ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="monde-gallery-item-content">
                            <h3><?php the_title(); ?></h3>
                            <p><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
                            <a href="<?php echo esc_url($link); ?>" class="monde-project-link">Pokaż projekt →</a>
                        </div>
                    </div>
                    <?php
                }
                wp_reset_postdata();
            } else {
                echo '<p>Nie znaleziono projektów.</p>';
            }
            ?>
        </div>
    </div>

    <style>
        .monde-gallery-wrapper {
            max-width: 1200px;
            margin: 0 auto;
        }

        .monde-gallery-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .monde-filter-btn {
            padding: 10px 20px;
            border: 2px solid #69b53f;
            background: white;
            color: #69b53f;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .monde-filter-btn:hover,
        .monde-filter-btn.active {
            background: #69b53f;
            color: white;
        }

        .monde-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
        }

        .monde-gallery-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .monde-gallery-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 12px 35px rgba(49, 169, 255, 0.2);
        }

        .monde-gallery-item-image {
            width: 100%;
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }

        .monde-gallery-category {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #69b53f 0%, #568c39ff 100%);
            color: white;
            padding: 5px 12px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .monde-gallery-item-content {
            padding: 20px;
        }

        .monde-gallery-item-content h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
            font-weight: 700;
            color: #10e51eff;
        }

        .monde-gallery-item-content p {
            margin: 0 0 15px 0;
            font-size: 13px;
            color: #718096;
            line-height: 1.5;
        }

        .monde-project-link {
            display: inline-block;
            color: #69b53f;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s ease;
        }

        .monde-project-link:hover {
            color: #69b53f;
            transform: translateX(5px);
        }

        @media (max-width: 768px) {
            .monde-gallery-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 15px;
            }
        }

        @media (max-width: 480px) {
            .monde-gallery-grid {
                grid-template-columns: 1fr;
            }

            .monde-gallery-filters {
                justify-content: flex-start;
                overflow-x: auto;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.monde-filter-btn');
            const galleryItems = document.querySelectorAll('.monde-gallery-item');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const filter = this.getAttribute('data-filter');
                    
                    filterBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    galleryItems.forEach(item => {
                        const itemCategories = item.getAttribute('data-categories');
                        
                        if (filter === 'all' || itemCategories.includes(filter)) {
                            item.style.display = 'block';
                            setTimeout(() => item.style.opacity = '1', 10);
                        } else {
                            item.style.opacity = '0';
                            setTimeout(() => item.style.display = 'none', 300);
                        }
                    });
                });
            });
        });
    </script>

    <?php
    return ob_get_clean();
}

register_activation_hook(__FILE__, 'monde_gallery_activate');

function monde_gallery_activate() {
    monde_register_project_post_type();
    monde_register_project_category();
    flush_rewrite_rules();
}

register_deactivation_hook(__FILE__, 'monde_gallery_deactivate');

function monde_gallery_deactivate() {
    flush_rewrite_rules();
}