<?php

namespace PhpArsenal\SalesforceBundle\Command;

use PhpArsenal\SoapClient\Client;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\ArrayInput;

/**
 * Fetch latest WSDL from Salesforce and store it locally
 */
#[AsCommand(
    name: 'salesforce:wsdl:refresh',
    description: 'Refresh Salesforce WSDL'
)]
class RefreshWsdlCommand extends Command
{
    public function __construct(
        private Client $soapClient,
        private string $wsdlPath
    )
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Updating the WSDL file');

        $this->downloadWsdl();

        if (!$input->getOption('no-cache-clear')) {
            $command = $this->getApplication()->find('cache:clear');
            $command->run(new ArrayInput(['command' => 'cache:clear']), $output);
        }

        return Command::SUCCESS;
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

        if (!\simplexml_load_string((string)$response->getBody())) {
            throw new \Exception('The downloaded WSDL is invalid. ' . sprintf('`%s`', (string)$response->getBody()));
        }

        file_put_contents($this->wsdlPath, (string)$response->getBody());
    }
}