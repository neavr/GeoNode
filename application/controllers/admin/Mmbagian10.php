<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mmbagian10 extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('HotspotModel','Model');
		$this->load->model('KecamatanModel');
	}

	public function index()
	{
		$datacontent['url']='admin/mmbagian10';
		$datacontent['title']='Halaman Mencoba Menjawab Bagian 10';
		// $datacontent['datatable']=$this->Model->get();
		$data['content']=$this->load->view('admin/mmbagian10/mapView',$datacontent,TRUE);
		$data['js']=$this->load->view('admin/mmbagian10/js/mapJs',$datacontent,TRUE);
		$data['title']=$datacontent['title'];
		$this->load->view('admin/layouts/html',$data);
	}
	public function kecamatancsv(){
		header('Content-Type: application/json');
		$file=FCPATH.'assets/csv/kecamatan.csv';
		$csv = array_map(function($v){return str_getcsv($v, ";");}, file($file));
		$i=0;
		foreach ($csv as $row) {
			if($i>0){
				$data[]=[
					'kd_kecamatan'=>$row[0],
					'nm_kecamatan'=>$row[1],
					'geojson_kecamatan'=>$row[2],
					'warna_kecamatan'=>$row[3]
				];
			}
			$i++;
		}
		echo "var dataKecamatan=".json_encode($data,JSON_PRETTY_PRINT);
	}
}
