<?php
class ControllerExtensionModuleOCNCategoryWallPro extends Controller {
	private $extension_version = '3.0.3.4';
	private $author = 'Hkr';
	private $author_repository = 'Hkr32';
	private $repository = 'GitHub';
	private $support_author = 'https://forum.opencart.name/members/hkr.3/';
	private $support_extension = 'https://forum.opencart.name/resources/32/';
	private $repository_author = 'https://github.com/Hkr32';
	private $repository_extension = 'https://github.com/ruOpenCart/category-wall-pro';
	private $url_extension_update = 'https://api.github.com/repos/ruOpenCart/category-wall-pro/releases/latest';

	private $error = [];

	public function index() {
		$this->load->language('extension/module/ocn_category_wall_pro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$module_id = isset($this->request->get['module_id']) ? $this->request->get['module_id'] : 0;
			if (!$module_id) {
				$this->model_setting_module->addModule('ocn_category_wall_pro', $this->request->post);
				$modules = $this->model_setting_module->getModulesByCode('ocn_category_wall_pro');
				$last = count($modules) - 1;
				$module_id = $modules[$last]['module_id'];
			} else {
				$this->model_setting_module->editModule($module_id, $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			// If button apply
			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module_id, true));
			}

			// Go to list modules
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}

		$this->getForm();
	}

	protected function getForm() {
		$default_data = [
			'module_ocn_category_wall_pro_status' => 0,
			'module_ocn_category_wall_pro_height_status' => 1,
			'module_ocn_category_wall_pro_image_status' => 1,
			'module_ocn_category_wall_pro_image_width' => 220,
			'module_ocn_category_wall_pro_image_height' => 220,
			'module_ocn_category_wall_pro_subcategory_status' => 1,
			'module_ocn_category_wall_pro_subcategory_limit' => 2,
			'module_ocn_category_wall_pro_subcategory_collapse_status' => 0,
			'module_ocn_category_wall_pro_description_status' => 1,
			'module_ocn_category_wall_pro_description_length' => 30,
			'module_ocn_category_wall_pro_categories_type' => 'all',
			'module_ocn_category_wall_pro_categories_selected' => [],
			'module_ocn_category_wall_pro_categories_custom' => []
		];

		$data['data_current_version'] = $this->extension_version;
		$data['data_author'] = $this->author;
		$data['data_author_repository'] = $this->author_repository;
		$data['data_repository'] = $this->repository;
		$data['data_support_author'] = $this->support_author;
		$data['data_support_extension'] = $this->support_extension;
		$data['data_repository_author'] = $this->repository_author;
		$data['data_repository_extension'] = $this->repository_extension;
		$data['url_extension_update'] = $this->url_extension_update;

		$data['user_token'] = $this->session->data['user_token'];
		$module_id = isset($this->request->get['module_id']) ? $this->request->get['module_id'] : 0;

		//Errors
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : [];
		$data['error_name'] = isset($this->error['name']) ? $this->error['name'] : [];
		$data['error_title'] = isset($this->error['title']) ? $this->error['title'] : [];
		$data['error_width'] = isset($this->error['width']) ? $this->error['width'] : [];
		$data['error_height'] = isset($this->error['height']) ? $this->error['height'] : [];
		$data['error_limit'] = isset($this->error['limit']) ? $this->error['limit'] : [];
		$data['error_length'] = isset($this->error['length']) ? $this->error['length'] : [];
		$data['error_categories_selected_minimum'] = isset($this->error['categories_selected_minimum']) ? $this->error['categories_selected_minimum'] : [];
		$data['error_categories_custom_minimum'] = isset($this->error['categories_custom_minimum']) ? $this->error['categories_custom_minimum'] : [];

		// Breadcrumbs
		$data['breadcrumbs'] = [];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		];
		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'] . ($module_id ? '&module_id=' . $module_id : ''), true)
		];

		// Buttons
		if (!isset($module_id)) {
			$data['url_action'] = $this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['url_action'] = $this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $module_id, true);
		}
		$data['url_cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($module_id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($module_id);
		}

		// Data form
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		foreach ($data['languages'] as $language) {
			if (isset($this->request->post['module_ocn_category_wall_pro_title'][$language['language_id']]['title'])) {
				$data['module_ocn_category_wall_pro_title'][$language['language_id']]['title'] = $this->request->post['module_ocn_category_wall_pro_title'][$language['language_id']]['title'];
			} elseif (!empty($module_info['module_ocn_category_wall_pro_title'][$language['language_id']]['title'])) {
				$data['module_ocn_category_wall_pro_title'][$language['language_id']]['title'] = $module_info['module_ocn_category_wall_pro_title'][$language['language_id']]['title'];
			} else {
				$data['module_ocn_category_wall_pro_title'][$language['language_id']]['title'] = $this->language->get('heading_title');
			}
		}
		if (isset($this->request->post['module_ocn_category_wall_pro_categories_selected'])) {
			$data['module_ocn_category_wall_pro_categories_selected'] = $this->request->post['module_ocn_category_wall_pro_categories_selected'];
		} elseif (!empty($module_info['module_ocn_category_wall_pro_categories_selected'])) {
			$data['module_ocn_category_wall_pro_categories_selected'] = $module_info['module_ocn_category_wall_pro_categories_selected'];
		} else {
			$data['module_ocn_category_wall_pro_categories_selected'] = $default_data['module_ocn_category_wall_pro_categories_selected'];
		}
		
		if (isset($this->request->post['module_ocn_category_wall_pro_categories_custom'])) {
			$data['module_ocn_category_wall_pro_categories_custom'] = $this->request->post['module_ocn_category_wall_pro_categories_custom'];
		} elseif (!empty($module_info['module_ocn_category_wall_pro_categories_custom'])) {
			$data['module_ocn_category_wall_pro_categories_custom'] = $module_info['module_ocn_category_wall_pro_categories_custom'];
		} else {
			$data['module_ocn_category_wall_pro_categories_custom'] = $default_data['module_ocn_category_wall_pro_categories_custom'];
		}

		$data['status'] = isset($this->request->post['status'])
			? $this->request->post['status']
			: (!empty($module_info) ? $module_info['status'] : $default_data['module_ocn_category_wall_pro_status']);
			
		$data['module_ocn_category_wall_pro_height_status'] = isset($this->request->post['module_ocn_category_wall_pro_height_status'])
			? $this->request->post['module_ocn_category_wall_pro_height_status']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_height_status'] : $default_data['module_ocn_category_wall_pro_height_status']);
			
		$data['module_ocn_category_wall_pro_image_status'] = isset($this->request->post['module_ocn_category_wall_pro_image_status'])
			? $this->request->post['module_ocn_category_wall_pro_image_status']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_image_status'] : $default_data['module_ocn_category_wall_pro_image_status']);
		
		$data['module_ocn_category_wall_pro_image_width'] = isset($this->request->post['module_ocn_category_wall_pro_image_width'])
			? $this->request->post['module_ocn_category_wall_pro_image_width']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_image_width'] : $default_data['module_ocn_category_wall_pro_image_width']);
		
		$data['module_ocn_category_wall_pro_image_height'] = isset($this->request->post['module_ocn_category_wall_pro_image_height'])
			? $this->request->post['module_ocn_category_wall_pro_image_height']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_image_height'] : $default_data['module_ocn_category_wall_pro_image_height']);
		
		$data['module_ocn_category_wall_pro_subcategory_status'] = isset($this->request->post['module_ocn_category_wall_pro_subcategory_status'])
			? $this->request->post['module_ocn_category_wall_pro_subcategory_status']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_subcategory_status'] : $default_data['module_ocn_category_wall_pro_subcategory_status']);
		
		$data['module_ocn_category_wall_pro_subcategory_limit'] = isset($this->request->post['module_ocn_category_wall_pro_subcategory_limit'])
			? $this->request->post['module_ocn_category_wall_pro_subcategory_limit']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_subcategory_limit'] : $default_data['module_ocn_category_wall_pro_subcategory_limit']);
		
		$data['module_ocn_category_wall_pro_subcategory_collapse_status'] = isset($this->request->post['module_ocn_category_wall_pro_subcategory_collapse_status'])
			? $this->request->post['module_ocn_category_wall_pro_subcategory_collapse_status']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_subcategory_collapse_status'] : $default_data['module_ocn_category_wall_pro_subcategory_collapse_status']);
		
		$data['module_ocn_category_wall_pro_description_status'] = isset($this->request->post['module_ocn_category_wall_pro_description_status'])
			? $this->request->post['module_ocn_category_wall_pro_description_status']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_description_status'] : $default_data['module_ocn_category_wall_pro_description_status']);
		
		$data['module_ocn_category_wall_pro_description_length'] = isset($this->request->post['module_ocn_category_wall_pro_description_length'])
			? $this->request->post['module_ocn_category_wall_pro_description_length']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_description_length'] : $default_data['module_ocn_category_wall_pro_description_length']);
		
		$data['module_ocn_category_wall_pro_categories_type'] = isset($this->request->post['module_ocn_category_wall_pro_categories_type'])
			? $this->request->post['module_ocn_category_wall_pro_categories_type']
			: (!empty($module_info) ? $module_info['module_ocn_category_wall_pro_categories_type'] : $default_data['module_ocn_category_wall_pro_categories_type']);

		// Categories
		$this->load->model('extension/module/ocn_category_wall_pro');
		$data['parent_categories'] = $this->model_extension_module_ocn_category_wall_pro->getParentCategories();
		$ids = $data['module_ocn_category_wall_pro_categories_custom'] ?: [];
		$data['custom_categories'] = count($ids) < 1
			? []
			: $this->model_extension_module_ocn_category_wall_pro->getCategoriesByIds($ids);

		// Templates
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/ocn_category_wall_pro', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/ocn_category_wall_pro')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();
		foreach ($data['languages'] as $language) {
			if ((utf8_strlen($this->request->post['module_ocn_category_wall_pro_title'][$language['language_id']]['title']) < 1) || (utf8_strlen($this->request->post['module_ocn_category_wall_pro_title'][$language['language_id']]['title']) > 255)) {
				$this->error['title'][$language['language_id']] = $this->language->get('error_title');
			}
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!$this->request->post['module_ocn_category_wall_pro_image_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_ocn_category_wall_pro_image_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}

		if (!$this->request->post['module_ocn_category_wall_pro_subcategory_limit'] && $this->request->post['module_ocn_category_wall_pro_subcategory_limit'] == '') {
			$this->error['limit'] = $this->language->get('error_limit');
		}

		if (!$this->request->post['module_ocn_category_wall_pro_description_length'] && $this->request->post['module_ocn_category_wall_pro_description_length'] == '') {
			$this->error['length'] = $this->language->get('error_length');
		}

		if ($this->request->post['module_ocn_category_wall_pro_categories_type'] == 'selected' && (!isset($this->request->post['module_ocn_category_wall_pro_categories_selected']) || count($this->request->post['module_ocn_category_wall_pro_categories_selected']) < 1)) {
			$this->error['categories_selected_minimum'] = $this->language->get('error_categories_minimum');
		}

		if ($this->request->post['module_ocn_category_wall_pro_categories_type'] == 'custom' && (!isset($this->request->post['module_ocn_category_wall_pro_categories_custom']) || count($this->request->post['module_ocn_category_wall_pro_categories_custom']) < 1)) {
			$this->error['categories_custom_minimum'] = $this->language->get('error_categories_minimum');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
