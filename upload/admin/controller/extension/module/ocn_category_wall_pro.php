<?php
class ControllerExtensionModuleOCNCategoryWallPro extends Controller {
	private $error = [];

	public function install() {
		$this->load->model('setting/setting');

		$data = [
			'module_ocn_category_wall_pro_status' => 0,
			'module_ocn_category_wall_pro_height_status' => 1,
			'module_ocn_category_wall_pro_image_status' => 1,
			'module_ocn_category_wall_pro_image_width' => 220,
			'module_ocn_category_wall_pro_image_height' => 220,
			'module_ocn_category_wall_pro_subcategory_status' => 1,
			'module_ocn_category_wall_pro_subcategory_limit' => 2,
			'module_ocn_category_wall_pro_description_status' => 1,
			'module_ocn_category_wall_pro_description_length' => 30,
			'module_ocn_category_wall_pro_categories_type' => 'all'
		];

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		foreach ($data['languages'] as $language) {
			$title = 'module_ocn_category_wall_pro_title--' . $language['language_id'];
			if ($language['code'] == 'ru-ru') {
				$data[$title] = '[OCN] Стена Категорий';
			} else {
				$data[$title] = '[OCN] Category Wall';
			}
		}

		$this->model_setting_setting->editSetting('module_ocn_category_wall_pro', $data);
	}

	public function index() {
		$this->load->language('extension/module/ocn_category_wall_pro');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('module_ocn_category_wall_pro', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			// If button apply
			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'], true));
			}

			// Go to list modules
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	protected function getForm() {
		$data['user_token'] = $this->session->data['user_token'];

		//Errors
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : [];
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
			'href' => $this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'], true)
		];

		// Buttons
		$data['url_action'] = $this->url->link('extension/module/ocn_category_wall_pro', 'user_token=' . $this->session->data['user_token'], true);
		$data['url_cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		// Data form
		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		foreach ($data['languages'] as $language) {
			$title = 'module_ocn_category_wall_pro_title--' . $language['language_id'];
			if (isset($this->request->post[$title])) {
				$data['module_ocn_category_wall_pro_title'][$language['language_id']] = $this->request->post[$title];
			} else {
				$data['module_ocn_category_wall_pro_title'][$language['language_id']] = $this->config->get($title);
			}
		}
		if (isset($this->request->post['module_ocn_category_wall_pro_categories_selected'])) {
			$data['module_ocn_category_wall_pro_categories_selected'] = $this->request->post['module_ocn_category_wall_pro_categories_selected'];
		} elseif (isset($this->error['categories_selected_minimum'])) {
			$data['module_ocn_category_wall_pro_categories_selected'] = [];
		} else {
			$data['module_ocn_category_wall_pro_categories_selected'] = $this->config->get('module_ocn_category_wall_pro_categories_selected');
		}
		if (isset($this->request->post['module_ocn_category_wall_pro_categories_custom'])) {
			$data['module_ocn_category_wall_pro_categories_custom'] = $this->request->post['module_ocn_category_wall_pro_categories_custom'];
		} elseif (isset($this->error['categories_custom_minimum'])) {
			$data['module_ocn_category_wall_pro_categories_custom'] = [];
		} else {
			$data['module_ocn_category_wall_pro_categories_custom'] = $this->config->get('module_ocn_category_wall_pro_categories_custom');
		}
		$data['module_ocn_category_wall_pro_status'] = isset($this->request->post['module_ocn_category_wall_pro_status'])
			? $this->request->post['module_ocn_category_wall_pro_status']
			: $this->config->get('module_ocn_category_wall_pro_status');
		$data['module_ocn_category_wall_pro_height_status'] = isset($this->request->post['module_ocn_category_wall_pro_height_status'])
			? $this->request->post['module_ocn_category_wall_pro_height_status']
			: $this->config->get('module_ocn_category_wall_pro_height_status');
		$data['module_ocn_category_wall_pro_image_status'] = isset($this->request->post['module_ocn_category_wall_pro_image_status'])
			? $this->request->post['module_ocn_category_wall_pro_image_status']
			: $this->config->get('module_ocn_category_wall_pro_image_status');
		$data['module_ocn_category_wall_pro_image_width'] = isset($this->request->post['module_ocn_category_wall_pro_image_width'])
			? $this->request->post['module_ocn_category_wall_pro_image_width']
			: $this->config->get('module_ocn_category_wall_pro_image_width');
		$data['module_ocn_category_wall_pro_image_height'] = isset($this->request->post['module_ocn_category_wall_pro_image_height'])
			? $this->request->post['module_ocn_category_wall_pro_image_height']
			: $this->config->get('module_ocn_category_wall_pro_image_height');
		$data['module_ocn_category_wall_pro_subcategory_status'] = isset($this->request->post['module_ocn_category_wall_pro_subcategory_status'])
			? $this->request->post['module_ocn_category_wall_pro_subcategory_status']
			: $this->config->get('module_ocn_category_wall_pro_subcategory_status');
		$data['module_ocn_category_wall_pro_subcategory_limit'] = isset($this->request->post['module_ocn_category_wall_pro_subcategory_limit'])
			? $this->request->post['module_ocn_category_wall_pro_subcategory_limit']
			: $this->config->get('module_ocn_category_wall_pro_subcategory_limit');
		$data['module_ocn_category_wall_pro_subcategory_collapse_status'] = isset($this->request->post['module_ocn_category_wall_pro_subcategory_collapse_status'])
			? $this->request->post['module_ocn_category_wall_pro_subcategory_collapse_status']
			: $this->config->get('module_ocn_category_wall_pro_subcategory_collapse_status');
		$data['module_ocn_category_wall_pro_description_status'] = isset($this->request->post['module_ocn_category_wall_pro_description_status'])
			? $this->request->post['module_ocn_category_wall_pro_description_status']
			: $this->config->get('module_ocn_category_wall_pro_description_status');
		$data['module_ocn_category_wall_pro_description_length'] = isset($this->request->post['module_ocn_category_wall_pro_description_length'])
			? $this->request->post['module_ocn_category_wall_pro_description_length']
			: $this->config->get('module_ocn_category_wall_pro_description_length');
		$data['module_ocn_category_wall_pro_categories_type'] = isset($this->request->post['module_ocn_category_wall_pro_categories_type'])
			? $this->request->post['module_ocn_category_wall_pro_categories_type']
			: $this->config->get('module_ocn_category_wall_pro_categories_type');

		// Categories
		$this->load->model('extension/module/ocn_category_wall_pro');
		$data['parent_categories'] = $this->model_extension_module_ocn_category_wall_pro->getParentCategories();
		$ids = $data['module_ocn_category_wall_pro_categories_custom']
			? implode(',', $data['module_ocn_category_wall_pro_categories_custom'])
			: 0;
		$data['custom_categories'] = ($ids == 0)
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
			$title = 'module_ocn_category_wall_pro_title--' . $language['language_id'];
			if ((utf8_strlen($this->request->post[$title]) < 1) || (utf8_strlen($this->request->post[$title]) > 255)) {
				$this->error['title'][$language['language_id']] = $this->language->get('error_title');
			}
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
