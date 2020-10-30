<?php


namespace App\Command;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class parseCommand extends Command
{
    protected static $defaultName = 'app:parse';

    protected function configure()
    {
        $this->setDescription('Parse amazon')
            ->addArgument('amazonUrl', InputArgument::REQUIRED, 'Url to item');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $html = file_get_contents($input->getArgument('amazonUrl'));

        $crawler = new Crawler($html);

        $title = $crawler->filterXPath('//*[@id="title"]')->text();

        $price = $crawler->filterXPath('//*[@id="price_inside_buybox"]')->text();

        $imageSrc = $crawler->filterXPath('//*[@id="imgTagWrapperId"]/img')->extract(array('data-a-dynamic-image'));
        $imageArr = explode("\"", $imageSrc[0]);

        $merchant = $crawler->filterXPath('//*[@id="tabular-buybox-truncate-1"]')->text();

        $description = $crawler->filterXPath('//*[@id="feature-bullets"]/ul/li')->filterXPath('//*[@class="a-list-item"]')->each(function (Crawler $node, $i){
            return $node->text();
        });
        
        echo "Title: \n" . $title . "\n\n";
        echo "Price: \n" . $price . "\n\n";
        echo "Image: \n" . $imageArr[1] . "\n\n";
        echo "Merchant: \n" . $merchant . "\n\n";
        echo "Description: \n";
        foreach ($description as $item){
            echo $item . "\n";
        };
        return 0;
    }
}