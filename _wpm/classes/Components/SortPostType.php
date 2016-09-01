<?php
namespace Wpm\Components;


class SortPostType {

	protected $cssUrl;
	protected $imgUrl;
	protected $jsUrl;
	protected $viewDir;
	protected $postTypes;

	protected $options = null;
	protected $optionVar = 'wpmSortOptions';

	protected $tax = null;
	protected $term = null;
	protected $postType = null;

	public function __construct()
	{
		$this->cssUrl = WPM_RESOURCES_URL . '/sortPostType/css';
		$this->imgUrl = WPM_RESOURCES_URL . '/sortPostType/img';
		$this->jsUrl = WPM_RESOURCES_URL . '/sortPostType/js';
		$this->viewDir = WPM_RESOURCES_DIR . '/sortPostType';

		if(is_admin()) {
			add_action('wp_ajax_wpm_reorder', [&$this, 'ajaxReorderList']);
			add_action('wp_ajax_wpm_reorder_toggle', [&$this, 'ajaxToggleId']);
			add_filter('wp_list_pages_excludes', array(&$this, 'excludeFromListPages'));
		}
	}
	
	
	public function add($postTypes=[])
	{
		$this->postTypes = $postTypes;
		add_action('admin_menu', [&$this, 'addSubMenu']);
	}
	
	
	public function addSubMenu()
	{
		if(function_exists('add_options_page')) {
			foreach($this->postTypes as $postType) {
				$page = add_submenu_page('edit.php?post_type='.$postType, "Order", "Order", 'edit_pages', $postType.'Reorder', [&$this, 'adminUi']);
				add_action("admin_print_scripts-$page", [&$this, 'adminScripts']);
				add_action("admin_print_styles-$page", [&$this, 'adminStyles']);
			}
		}
	}


	public function getOptions()
	{
		$options = get_option($this->optionVar);
		if($options) {
			$this->options = unserialize($options);
		} else {
			$this->options = [];
		}
	}


	public function setOptions()
	{
		update_option($this->optionVar, serialize($this->options));
	}


	public function adminUi()
	{
		if(empty($_GET['post_type'])) return;

		$this->postType = esc_attr($_GET['post_type']);
		$this->tax = (isset($_GET['reorder_tax']) ? esc_attr($_GET['reorder_tax']) : false);
		$this->term = (isset($_GET['reorder_term']) ? esc_attr($_GET['reorder_term']) : false);

		$typeObj = get_post_type_object($this->postType);
		$title = $typeObj->labels->name;

		$this->getOptions();

		require_once( $this->viewDir . '/list.php' );
	}


	public function adminScripts()
	{
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpm-sort_post_types-interface', $this->jsUrl . '/interface-1.2.js', array('jquery'));
		wp_enqueue_script('wpm-sort_post_types-nested', $this->jsUrl . '/inestedsortable.js', array('wpm-sort_post_types-interface'));
		wp_enqueue_script('wpm-sort_post_types', $this->jsUrl . '/sort-post-types.js', array('wpm-sort_post_types-nested'));
	}


	public function adminStyles()
	{
		wp_enqueue_style('wpm-sort_post_types', $this->cssUrl.'/sort-post-types.css');
	}


	public function buildList($postParent=0)
	{
		$posts = $this->getItems($postParent);

		foreach($posts as $post):
			$status = ($post->post_status != 'publish' ? '<span>' . strtoupper($post->post_status) . '</span>' : "");
			$title = ($post->post_title ? $post->post_title : '(no title)') . $status;
			?>			

			<li id="listItem_<?php echo $post->ID; ?>" class="clear-element page-item <?php echo $post->post_status; ?>">
				<table class="reorder-inner">
					<tr>
						<td>
							<strong><?php echo $title; ?></strong>
							<span id="postDisplay-<?php echo $post->ID; ?>" class="post-display">
								<?php if(in_array($post->ID, $this->options)) echo 'HIDDEN'; ?>
							</span>
						</td>
						<td width="80" class="sort-action">
							<a href="#" class="show-hide-toggle" id="<?php echo $post->ID; ?>">Toggle</a>
						</td>
					</tr>
				</table>

				<?php
				if(is_post_type_hierarchical($this->postType)) {
					echo '<ul class="page-list">';
					$this->buildList($post->ID);
					echo '</ul>';
				}
				?>

			</li>

			<?php
		endforeach;
	}


	public function getItems($postParent)
	{
		$query = wpm('q.' . $this->postType)
			->parent($postParent)
			->status(['publish', 'password', 'draft', 'private'])
			->order('menu_order', 'asc');

		if($this->tax and $this->term) {
			$query->tax($this->tax, $this->term);
		}

		return $query->get();
	}


	public function ajaxReorderList()
	{
		$this->saveReorder($_POST['sort']['order-posts-list-nested']);	
	}


	public function saveReorder($data, $parentId=0)
	{
		$menuOrder = 0;

		foreach($data as $post) {
			$id = (int) str_replace('listItem_', '', $post['id']);

			if(is_numeric($id)) {
				$args = [
					'ID' => $id,
					'menu_order' => $menuOrder,
					'post_parent' => $parentId
				];
				wp_update_post($args);

				if(!empty($post['children'])) {
					$this->saveReorder($post['children'], $id);
				}
			} else {
				echo $id . ',';
			}

			$menuOrder++;
		}
	}


	public function ajaxToggleId() {
		if(!empty($_POST['id']) and is_numeric($_POST['id'])) {
			$id = $_POST['id'];
			$this->getOptions();

			if(in_array($id, $this->options)) {
				$key = array_search($id, $this->options);
				if($key !== false) {
					unset($this->options[$key]);
					echo '';
				}
			} else {
				$this->options[] = $id;
				echo 'HIDDEN';
			}

			$this->setOptions();
		}
		die();
	}


	public function excludeFromListPages($excludeArray) {
		$this->getOptions();

		if($this->options) {
			$excludeArray = array_merge($this->options, $excludeArray);
			sort($excludeArray);
		}

		return $excludeArray;
	}
}