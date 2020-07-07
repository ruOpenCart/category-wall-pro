<?php
class ControllerExtensionModuleOCNCategoryWall extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/ocn_category_wall');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('module_ocn_category_wall', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');
			
			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->url->link('extension/module/ocn_category_wall', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
			}

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		$this->getForm();
	}
	
	public function install() {
		$this->load->model('setting/setting');
		$data = [
			'module_ocn_category_wall_status' => 0,
			'module_ocn_category_wall_title' => '[OCN] Стена Категорий',
			'module_ocn_category_wall_image_status' => 1,
			'module_ocn_category_wall_image_width' => 220,
			'module_ocn_category_wall_image_height' => 80,
			'module_ocn_category_wall_subcategory_status' => 1,
			'module_ocn_category_wall_subcategory_limit' => 2,
			'module_ocn_category_wall_description_status' => 1,
			'module_ocn_category_wall_description_length' => 30
		];
		$this->model_setting_setting->editSetting('module_ocn_category_wall', $data);
	}
	
	protected function getForm() {
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = array();
		}
		
		if (isset($this->error['width'])) {
			$data['error_width'] = $this->error['width'];
		} else {
			$data['error_width'] = array();
		}
		
		if (isset($this->error['height'])) {
			$data['error_height'] = $this->error['height'];
		} else {
			$data['error_height'] = array();
		}
		
		if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = array();
		}
		
		if (isset($this->error['length'])) {
			$data['error_length'] = $this->error['length'];
		} else {
			$data['error_length'] = array();
		}
		
		$data['breadcrumbs'] = array();
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/ocn_category_wall', 'user_token=' . $this->session->data['user_token'], true)
		);
		
		$data['action'] = $this->url->link('extension/module/ocn_category_wall', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->post['module_ocn_category_wall_status'])) {
			$data['module_ocn_category_wall_status'] = $this->request->post['module_ocn_category_wall_status'];
		} else {
			$data['module_ocn_category_wall_status'] = $this->config->get('module_ocn_category_wall_status');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_title'])) {
			$data['module_ocn_category_wall_title'] = $this->request->post['module_ocn_category_wall_title'];
		} else {
			$data['module_ocn_category_wall_title'] = $this->config->get('module_ocn_category_wall_title');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_image_status'])) {
			$data['module_ocn_category_wall_image_status'] = $this->request->post['module_ocn_category_wall_image_status'];
		} else {
			$data['module_ocn_category_wall_image_status'] = $this->config->get('module_ocn_category_wall_image_status');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_image_width'])) {
			$data['module_ocn_category_wall_image_width'] = $this->request->post['module_ocn_category_wall_image_width'];
		} else {
			$data['module_ocn_category_wall_image_width'] = $this->config->get('module_ocn_category_wall_image_width');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_image_height'])) {
			$data['module_ocn_category_wall_image_height'] = $this->request->post['module_ocn_category_wall_image_height'];
		} else {
			$data['module_ocn_category_wall_image_height'] = $this->config->get('module_ocn_category_wall_image_height');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_subcategory_status'])) {
			$data['module_ocn_category_wall_subcategory_status'] = $this->request->post['module_ocn_category_wall_subcategory_status'];
		} else {
			$data['module_ocn_category_wall_subcategory_status'] = $this->config->get('module_ocn_category_wall_subcategory_status');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_subcategory_limit'])) {
			$data['module_ocn_category_wall_subcategory_limit'] = $this->request->post['module_ocn_category_wall_subcategory_limit'];
		} else {
			$data['module_ocn_category_wall_subcategory_limit'] = $this->config->get('module_ocn_category_wall_subcategory_limit');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_description_status'])) {
			$data['module_ocn_category_wall_description_status'] = $this->request->post['module_ocn_category_wall_description_status'];
		} else {
			$data['module_ocn_category_wall_description_status'] = $this->config->get('module_ocn_category_wall_description_status');
		}
		
		if (isset($this->request->post['module_ocn_category_wall_description_length'])) {
			$data['module_ocn_category_wall_description_length'] = $this->request->post['module_ocn_category_wall_description_length'];
		} else {
			$data['module_ocn_category_wall_description_length'] = $this->config->get('module_ocn_category_wall_description_length');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/module/ocn_category_wall', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/ocn_category_wall')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ((utf8_strlen($this->request->post['module_ocn_category_wall_title']) < 1) || (utf8_strlen($this->request->post['module_ocn_category_wall_title']) > 255)) {
			$this->error['title'] = $this->language->get('error_title');
		}
		
		if (!$this->request->post['module_ocn_category_wall_image_width']) {
			$this->error['width'] = $this->language->get('error_width');
		}
		
		if (!$this->request->post['module_ocn_category_wall_image_height']) {
			$this->error['height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_ocn_category_wall_subcategory_limit'] && $this->request->post['module_ocn_category_wall_subcategory_limit'] == '') {
			$this->error['limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['module_ocn_category_wall_description_length'] && $this->request->post['module_ocn_category_wall_description_length'] == '') {
			$this->error['length'] = $this->language->get('error_length');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
