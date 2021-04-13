<?php
namespace PhpArsenal\SalesforceBundle\Command;

use PhpArsenal\SoapClient\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Fetch latest WSDL from Salesforce and store it locally
 *
 * @author David de Boer <david@ddeboer.nl>
 */
class RefreshWsdlCommand extends Command
{
    /** @var Client */
    private $soapClient;

    private const COMMAND_NAME = 'salesforce:wsdl:refresh';

    /**
     * RefreshWsdlCommand constructor.
     * @param Client $soapClient
     */
    public function __construct(Client $soapClient)
    {
        parent::__construct(self::COMMAND_NAME);

        $this->soapClient = $soapClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('Refresh Salesforce WSDL')
            ->setHelp(
                'Refreshing the WSDL itself requires a WSDL, so when using this'
                . 'command for the first time, please download the WSDL '
                . 'manually from Salesforce'
            )
            ->addOption(
                'no-cache-clear',
                'c',
                InputOption::VALUE_NONE,
                'Do not clear cache after refreshing WSDL'
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Updating the WSDL file');

        $this->downloadWsdl();

        if (!$input->getOption('no-cache-clear')) {
            $command = $this->getApplication()->find('cache:clear');

            $arguments = array(
                'command' => 'cache:clear',
            );
            $input = new ArrayInput($arguments);
            $command->run($input, $output);
        }
    }

    public function downloadWsdl(): void
    {
        /** @var \PhpArsenal\SoapClient\Client $client */
        $client = $this->soapClient;

        // Get current session id
        $loginResult = $client->getLoginResult();
        $sessionId = $loginResult->getSessionId();

        // type=* for enterprise WSDL
        $response = (new \GuzzleHttp\Client())->request('GET', vsprintf('https://%s.my.salesforce.com/soap/wsdl.jsp?type=*', [
            $loginResult->getServerInstance()
        ]), [
            'headers' => [
                'Cookie' => sprintf('sid=%s', $sessionId),
            ],
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSLVERSION => 6,
            ]
        ]);

        $wsdlFile = $this->getContainer()->getParameter('salesforce.soap_client.wsdl');

        if(!\simplexml_load_string((string)$response->getBody())) {
            throw new \Exception('The downloaded WSDL is invalid. ' . sprintf('`%s`', (string)$response->getBody()));
        }

        file_put_contents($wsdlFile, (string)$response->getBody());
    }
}

