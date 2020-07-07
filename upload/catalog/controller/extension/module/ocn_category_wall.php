<?php
class ControllerExtensionModuleOCNCategoryWall extends Controller {
	public function index() {
		$this->document->addStyle('catalog/view/theme/default/stylesheet/ocn/category-wall.css');

		$this->load->model('catalog/category');
		$this->load->model('extension/module/ocn_category_wall');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');

		$data['title'] = $this->config->get('module_ocn_category_wall_title');
		
		$category_id = 0;
		$limit = $this->config->get('module_ocn_category_wall_subcategory_limit');
		$length = $this->config->get('module_ocn_category_wall_description_length');

		$categories = $this->model_extension_module_ocn_category_wall->getCategories($category_id);

		foreach ($categories as $category) {
			// Level 2
			$children_data = array();

			if ($this->config->get('module_ocn_category_wall_subcategory_status')) {
				$children = $this->model_extension_module_ocn_category_wall->getCategories($category['category_id'], $limit);

				foreach ($children as $child) {
					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'],
						'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}
			}
			
			// Level 1
			$image = '';
			$image_status = $this->config->get('module_ocn_category_wall_image_status');
			if ($image_status) {
				if ($category['image']) {
					$image = $this->model_tool_image->resize($category['image'], $this->config->get('module_ocn_category_wall_image_width'), $this->config->get('module_ocn_category_wall_image_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('module_ocn_category_wall_image_width'), $this->config->get('module_ocn_category_wall_image_height'));
				}
			}
			
			$description = '';
			if ($this->config->get('module_ocn_category_wall_description_status') && $category['description'] && $category['description'] != '') {
				$description = trim(strip_tags(html_entity_decode($category['description'], ENT_QUOTES, 'UTF-8')));
				if ($length) {
					$description = utf8_substr($description, 0, $length) . '...';
				}
			}
			
			$data['categories'][] = array(
				'category_id' => $category_id,
				'name'        => $category['name'],
				'image'       => $image,
				'image_status'=> $image_status,
				'description' => $description,
				'children'    => $children_data,
				'href'        => $this->url->link('product/category', 'path=' . $category['category_id'])
			);
		}

		return $this->load->view('extension/module/ocn_category_wall', $data);
	}
}
