<?php
class ControllerExtensionModuleOCNCategoryWallPro extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/ocn_category_wall_pro');

		$this->load->model('catalog/category');
		$this->load->model('extension/module/ocn_category_wall_pro');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['title'] = $setting['module_ocn_category_wall_pro_title'][$this->config->get('config_language_id')]['title'];

		$category_id = 0;
		$limit = $setting['module_ocn_category_wall_pro_subcategory_limit'];
		$length = $setting['module_ocn_category_wall_pro_description_length'];
		$type = $setting['module_ocn_category_wall_pro_categories_type'];

		$ids = $type == 'all' ? [] : ($type == 'selected'
			? $setting['module_ocn_category_wall_pro_categories_selected']
			: $setting['module_ocn_category_wall_pro_categories_custom']);
		
		$categories = [];
		switch ($type) {
			case 'selected':
				$categories = $this->model_extension_module_ocn_category_wall_pro->getCategoriesByIds($ids);
				break;
			case 'custom':
				$categories = $this->model_extension_module_ocn_category_wall_pro->getCategoriesByIds($ids);
				break;
			default:
				$categories = $this->model_extension_module_ocn_category_wall_pro->getCategories();
				break;
		}

		$data['height_status'] = $setting['module_ocn_category_wall_pro_height_status'];
		$data['image_status'] = $setting['module_ocn_category_wall_pro_image_status'];
		$data['subcategory_collapse_status'] = $setting['module_ocn_category_wall_pro_subcategory_collapse_status'];

		foreach ($categories as $category) {
			// Level 2
			$children_data = [];

			if ($setting['module_ocn_category_wall_pro_subcategory_status']) {
				$children = $this->model_extension_module_ocn_category_wall_pro->getCategories($category['category_id'], $limit);

				foreach ($children as $child) {
					$children_data[] = [
						'category_id' => $child['category_id'],
						'name' => $child['name'],
						'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					];
				}
			}

			// Level 1
			$image = '';
			if ($data['image_status']) {
				if ($category['image']) {
					$image = $this->model_tool_image->resize($category['image'], $setting['module_ocn_category_wall_pro_image_width'], $setting['module_ocn_category_wall_pro_image_height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['module_ocn_category_wall_pro_image_width'], $setting['module_ocn_category_wall_pro_image_height']);
				}
			}

			$description = '';
			if ($setting['module_ocn_category_wall_pro_description_status'] && $category['description'] && $category['description'] != '') {
				$description = trim(strip_tags(html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8')));
				if ($length) {
					$description = utf8_substr($description, 0, $length) . '...';
				}
			}

			$data['categories'][] = [
				'category_id'     => $category['category_id'],
				'name'            => $category['name'],
				'image'           => $image,
				'description'     => $description,
				'children'        => $children_data,
				'href'            => $this->url->link('product/category', 'path=' . $category['category_id'])
			];
		}

		return $this->load->view('extension/module/ocn_category_wall_pro', $data);
	}
}
