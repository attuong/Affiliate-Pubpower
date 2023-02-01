<?php

require realpath(dirname(__FILE__)) . '/System.php';

use core\Model;

class Scan extends Model
{

    private $vliAdsTxt;
    private $dbtable = 'hbd_ads_txt_scan';

    public function __construct($config = [])
    {
        parent::__construct($config);
        
        $this->scan();
    }

    public function scan()
    {
        $this->getVliAdsTxt();
        $time = time() - (3600 * 8);
        $domains = $this->setTable(TABLE_DOMAIN)->find(
            [
                'impressions' => ['>' => 0],
                'last_scan_adstxt' => ['<' => $time]
            ],
            ['id', 'name'],
            ['revenue' => 'DESC'],
            10
        );

        foreach($domains as $item){
            $pubAdsTxt = $this->getAdsTxt($item->name);
            if($pubAdsTxt == ''){
                $this->updateScanStatus(
                    $item->id,
                    ['status' => 'error to get ads.txt', 'last_scan_time' => time()]
                );
                continue;
            }

            $checking = $this->checkAdsTxt(
                array_filter(explode("\n", $pubAdsTxt))
            );
            $this->updateScanStatus(
                $item->id,
                [
                    'missing' => implode("\n", $checking),
                    'missing_line' => count($checking),
                    'last_scan_txt' => $pubAdsTxt,
                    'last_scan_time' => time(),
                    'status' => 'scan complete'
                ]
            );

            $this->setTable(TABLE_DOMAIN)->update(
                ['id' => $item->id],
                ['last_scan_adstxt' => time()]
            );
        }

    }

    private function getVliAdsTxt()
    {
        $data = $this->setTable(TABLE_SETTINGS)->findOne(
            ['type' => 'general_config'],
            ['ads_txt']
        );

        $arr = array_filter(explode("\n", $data->ads_txt));
        $this->vliAdsTxt = [];
        foreach($arr as $k => $value){
            $key = $this->stripSpecialString($value);
            if($key != ''){
                $this->vliAdsTxt[$key] = $value;
            }
        }
    }

    private function checkAdsTxt($pubAdsTxt)
    {
        $masterAdsTxt = $this->vliAdsTxt;
        foreach($pubAdsTxt as $v){
            $findKey = $this->stripSpecialString($v);
            if($findKey != '' && isset($masterAdsTxt[$findKey])){
                unset($masterAdsTxt[$findKey]);
            }
        }
        return $masterAdsTxt;
    }

    private function updateScanStatus($domainID, $data)
    {
        $item = $this->setTable($this->dbtable)->findOne(
            ['domain_id' => $domainID]
        );

        if($item){
            $this->setTable($this->dbtable)->update(
                ['id' => $item->id],
                $data
            );
        }else{
            $data['domain_id'] = $domainID;
            $this->setTable($this->dbtable)->insert($data);
        }

    }

    private function getAdsTxt($domain)
    {
        $url = 'https://' . $domain . '/ads.txt';
        $result = $this->curl($url);
        if($result != ''){
            return $this->filterHtml($result);
        }

        $url = 'http://' . $domain . '/ads.txt';
        return $this->filterHtml($this->curl($url));
    }

    private function filterHtml($str){
        if(strpos($str, '<html') !== false || strpos($str, '<head>') !== false || strpos($str, '<body>') !== false){
            return '';
        }
        return $str;
    }

    private function stripSpecialString($str)
    {
        $str = str_replace('#display', '', $str);
        $str = str_replace('#banner, US', '', $str);
        $str = str_replace('#banner, CA', '', $str);
        $str = str_replace('#video, US', '', $str);
        $str = str_replace('#video, CA', '', $str);
        $str = str_replace('#Valueimpression', '', $str);
        $str = str_replace('#valueimpression', '', $str);
        $str = str_replace(' ', '-', $str);
        $str = preg_replace('/[^A-Za-z0-9\-]/', '', $str);
        $str = strtolower(trim(preg_replace('/\s+/', ' ', $str)));
        return $str;
    }

    public function curl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch,CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/75.0.3770.100 Safari/537.36',
            'Connection: keep-alive',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3'
        ));
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}

new Scan;
