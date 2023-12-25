<?php
	namespace App\Service;
	
	use Endroid\QrCode\Bacon\ErrorCorrectionLevelConverter;
	use Endroid\QrCode\Builder\BuilderInterface;
	use Endroid\QrCode\Color\Color;
	use Endroid\QrCode\Encoding\Encoding;
	use Endroid\QrCode\QrCode;
	use Endroid\QrCode\Label\Label;
	use Endroid\QrCode\Logo\Logo;
	use Endroid\QrCode\Writer\PngWriter;
	use Endroid\QrCode\Writer\SvgWriter;
	use Endroid\QrCode\Label\Font\NotoSans;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	
	
	class QrCodeService extends AbstractController
	{
		public function __construct(
			private BuilderInterface $builder
		)
		{
		}
		
		public function createQrFromLink($url,$name):string
		{
			$writer = new svgWriter();
	
			$qr = $this->builder->data($url)
				->encoding(new Encoding('UTF-8'))
				->size(300)
				->margin(10)
				->writer($writer)
				->writerOptions([
					'exclude_xml_declaration' => true,
					'exclude_margin' => true,
					'background_color' => '#ffffff',
					'foreground_color' => '#000000',
				])
				->build();
			$qr->saveToFile($this->getParameter('qr_directory').'/qr_'.$name.'.svg');
			
			return '/qr_'.$name.'.svg';
		}
	}