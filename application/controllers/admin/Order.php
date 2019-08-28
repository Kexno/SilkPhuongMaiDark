<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends Admin_Controller
{
    protected $_data;
    protected $_data_product;
    protected $_lang_code;
    protected $_data_location;

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('ion_auth'));
        $this->load->helper('status_order');
        //tải file ngôn ngữ
        $this->lang->load(array('order', 'column'));
        //tải model
        $this->load->model(['account_model', 'order_model', 'location_model', 'product_model']);
        $this->_data = new Order_model();
        $this->account = new Account_model();
        $this->_data_location = new Location_model();
        $this->_data_product = new Product_model();
        $this->_lang_code = $this->session->public_lang_code;
    }

    public function index($filter='')
    {
        $data['heading_title'] = 'Đơn hàng';
        $data['heading_description'] = "Danh sách đơn hàng";
        /*Breadcrumbs*/
        $this->breadcrumbs->push('Home', base_url());
        $this->breadcrumbs->push($data['heading_title'], '#');
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['status']=$filter;
        $data['main_content'] = $this->load->view($this->template_path . 'order/index', $data, TRUE);
        $this->load->view($this->template_main, $data);
    }

    public function ajax_list($status_order='')
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $length = $this->input->post('length');
            $no = $this->input->post('start');
            $page = $no / $length + 1;
            $params['page'] = $page;
            $params['limit'] = $length;
            if($this->input->post('is_status')!=''){ 
                $params['is_status'] = $this->input->post('is_status');
            }else{
                $params['is_status']=$status_order;
            }
            $list = $this->_data->getDataOrder($params);
            $this->getDataOrder($list);
            $data = array();
            foreach ($list as $item) {
                $no++;
                $row = array();
                $row[] = $item->id;
                $row[] = $item->code;
                $row[] = $item->full_name;
                $row[] = $item->phone;
                //$row[] = !empty($item->total) ? formatMoney($item->total) . 'đ' : '';
                switch ($item->is_status) {
                    case '1':
                    $row[] = '<span class="label label-default btnUpdateStatus" data-value="' . $item->is_status . '" >Đang xử lý</span>';
                    break;
                    case '2':
                    $row[] = '<span class="label label-success btnUpdateStatus" data-value="' . $item->is_status . '" >Đã xử lý</span>';
                    break;
                    default:
                    $row[] = '<span class="label label-default btnUpdateStatus" data-value="' . $item->is_status . '" >Hủy</span>';
                    break;
                }
                $row[] = formatDate($item->created_time);
                $action = button_action($item->id, ['edit']);
                
                $row[] = $action;
                $row[] = $action;
                $data[] = $row;
            }
            $output = array(
                "draw"            => $this->input->post('draw'),
                "recordsTotal"    => $this->_data->getTotalAll(),
                "recordsFiltered" => $this->_data->getTotal($params),
                "data"            => $data,
            );
            echo json_encode($output);
        }
        exit;
    }

    public function ajax_view($id)
    {
        $item = $this->_data->get_order($id);
        $datapr=$this->_data_product->getById($item->ward_id,'','vi');
        $this->getDataOrder($item);
        $item->created_time = formatDate($item->created_time);
        $item->city = $this->list_option($item->city_id);
        $item->district = $this->list_option($item->district_id,$type='district',$item->city_id);
        $item->ward = $this->list_option($item->ward_id,'ward',$item->district_id);
        $data['title'] = $datapr->title;
        $data['price'] = formatMoney($datapr->price).'đ';
        $item->ward_id = $data;
        die(json_encode($item));
    }

    public function list_option($id,$type='city',$parent_id=0,$kind=''){    
        if($type=='city') $data=$this->_data_location->getAllLocaltion('city');
        else if($type=='district') $data=$this->_data_location->getAllLocaltion('district','*',$parent_id);
        else $data=$this->_data_location->getAllLocaltion('ward','*',$parent_id);
        $html='';
        foreach ($data as $key => $value) {
            if($id==$value->id) $html.='<option value='.$value->id.' selected >'.$value->title.'</option>';
            else $html.='<option value='.$value->id.'>'.$value->title.'</option>';
        }
        if(!empty($kind)) die(json_encode($html));
        return $html;

    }

    public function ajax_update()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $data_store = $this->input->post();
            $id = $data_store['id'];
            unset($data_store['id']);
            $response = $this->_data->update(array('id' => $id), $data_store);
            if ($response == true) {
                $message['type'] = 'success';
                $message['message'] = 'Cập nhật thành công!.';
            } else {
                $message['message'] = 'Cập nhật không thành công!.';
                $message['type'] = 'error';
            }
            die(json_encode($message));
        }
    }

    public function ajax_update_field()
    {
        if ($this->input->server('REQUEST_METHOD') == 'POST' && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            $id = $this->input->post('id');
            $field = $this->input->post('field');
            $value = $this->input->post('value');
            $response = $this->_data->update(['id' => $id], [$field => $value]);
            if ($response != false) {
                $message['type'] = 'success';
                $message['message'] = $this->lang->line('mess_update_success');
            } else {
                $message['type'] = 'error';
                $message['message'] = $this->lang->line('mess_update_unsuccess');
            }
            print json_encode($message);
        }
        exit;
    }

    private function sendMail($data)
    {
        if (!empty($data->email)) {
            /*Config setting*/
            $this->load->library('email');
            $emailTo = $data->email; //Send mail cho khach hang
            $emailToCC = $data->email; //Send mail cho ban quan tri
            $emailFrom = $emailToCC;
            $nameFrom = $data->full_name;
            $contentHtml = '
            <h2>Thông tin mã kích hoạt khóa học</h2></br>

            <p>Họ và tên: ' . $data->full_name . '</p>
            <p>Email: ' . $data->email . '</p>
            <p>Số điện thoại: ' . $data->phone . '</p>
            <p>Mã kích hoạt: ' . $data->activation_code . '</p>
            <p>Vui lòng truy cập website ' . base_url() . ' để kích hoạt khóa học !</p>
            ';

            $this->email->from($emailFrom, $nameFrom);

            $this->email->to($emailTo);
            if (!empty($emailToCC)) $this->email->cc($emailToCC);
            if (!empty($emailToBCC)) $this->email->bcc($emailToBCC);
            $this->email->subject('Thông tin mã kích hoạt khóa học từ ' . base_url());
            $this->email->message($contentHtml);
            $this->email->send();
        }
    }

    public function ajax_delete($id)
    {
        $response = $this->_data->delete(['id' => $id]);
        if ($response != true) {
            $message['type'] = 'error';
            $message['message'] = "Xóa bản ghi thất bại !";
        } else {
            $message['type'] = 'success';
            $message['message'] = "Xóa bản ghi thành công !";
        }
        die(json_encode($message));
    }

    public function export_excel()
    {
        $this->load->library(array('PHPExcel'));
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator($this->config->item('cms_title'))
        ->setTitle($this->lang->line('heading_title'));


        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'ID')
        ->setCellValue('B1', lang('col_name'))
        ->setCellValue('C1', lang('col_address'))
        ->setCellValue('D1', lang('col_product_name'))
        ->setCellValue('E1', lang('col_total_amount'))
        ->setCellValue('F1', lang('col_created_at'))
        ->setCellValue('H1', lang('col_shipped_time'))
        ->setCellValue('I1', lang('col_order_status'));

        $list = $this->_data->getDataArr();
        $i = 2;
        foreach ($list as $item) {
            // dd($item);
            // $oneTransport = $this->_data->getTransport($item->transport_id);
            $oneTransport = $this->_data->getTransport(1);
            switch ($item->is_status) {
                case 0:
                $status = 'Đã hủy đơn hàng';
                break;
                case 1:
                $status = 'Đơn hàng chờ xử lý';
                break;
                case 2:
                $status = 'Đang vận chuyển';
                break;
                default:
                $status = 'Hoàn thành đơn hàng';
                break;
            }


            $listProduct = $this->_data->get_by_order_id($item->id);
            $productName = '';
            if (!empty($listProduct)) foreach ($listProduct as $k => $product) {
                $productModel = new Product_model();
                $oneProduct = getProduct($product->product_id);
                if ($k != 0) $productName .= ',';
                $productName .= $oneProduct->title;
            }
            // Miscellaneous glyphs, UTF-8
            $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $i, $item->id)
            ->setCellValue('B' . $i, $item->fullname)
            ->setCellValue('C' . $i, $item->address)
            ->setCellValue('D' . $i, $productName)
            ->setCellValue('E' . $i, number_format($item->total_amount))
            ->setCellValue('F' . $i, date('d/m/Y', strtotime($item->created_time)))
            ->setCellValue('H' . $i, date('d/m/Y', strtotime($item->shipped_time)))
            ->setCellValue('I' . $i, $status);
            $i++;
        }
        $nameFile = $this->lang->line('heading_title') . '_' . time();
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle($nameFile);


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

        header('Content-Disposition: attachment;filename="' . $nameFile . '.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    private function getDataOrder(&$orders)
    {
        if (is_array($orders)) {
            foreach ($orders as $value) {
                $product_id = array();
                $total = 0;
                $products = $this->_data->get_order_detail($value->id);
                if (!empty($products)) foreach ($products as $product) {
                    $total += $product->price * $product->quantity;
                    array_push($product_id, $product->product_id);
                }
                if (!empty($product_id)) $value->products = $this->_data_product->getById($product_id, '*', $this->_lang_code);
                $value->total = $total;
            }
        } else {
            $total = 0;
            $products = $this->_data->get_order_detail($orders->id);
            if (!empty($products)) foreach ($products as $product) {
                $total += $product->price * $product->quantity;
                $product->product = $this->_data_product->getById($product->product_id, '*', $this->_lang_code);
                $orders->product_item[] = $product;
            }
            $orders->total = formatMoney($total).' đ';
        }

    }

}