<?php

namespace Ealore\Mnemo\Command;

use Ealore\Mnemo\Interfaces\Wordlist;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MnemoCommand extends Command
{
    private int $limit = 5;
    private ?SymfonyStyle $io = null;

    protected static $defaultName = 'generate';
    protected static $defaultDescription = "Generates a list of random mnemonic passwords";

    protected function configure(): void
    {
        $this
            ->setHelp("The generate command outputs a list of {$this->limit} passwords")
            ->addOption('no-dashes', 'd', InputOption::VALUE_NONE, 'Remove dashes')
            ->addOption('scrambled', 's', InputOption::VALUE_NONE, 'Scrambled')
            ->addOption('max-length', 'l', InputOption::VALUE_REQUIRED, 'Max length')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $noDashes = (bool) $input->getOption('no-dashes');
        $scrambled = (bool) $input->getOption('scrambled');
        $maxLength = (int) $input->getOption('max-length');

        foreach ($this->generatePasswords($this->limit, $noDashes) as $password) {
            if ($scrambled) {
                $password = $this->scramble($password);
            }

            if ($maxLength > 0) {
                $password = substr($password, 0, $maxLength);
            }

            $this->io->text($password);
        }

        $this->io->success('Completed!');

        return Command::SUCCESS;
    }

    /**
     * @param int $limit
     *
     * @return array|string[]
     */
    private function generatePasswords(int $limit, bool $noDashes = false): array
    {
        $passwords = [];
        $iterations = 1024;
        $count = 0;
        while (count($passwords) < $limit) {
            $passwords[] = $this->generateMnemonicPassword($noDashes);
            $passwords = array_unique(array_filter($passwords));
            $count++;

            if ($count > $iterations) {
                throw new \RuntimeException("Could not find {$limit} unique passwords after {$iterations} attempts.");
            }
        }

        return $passwords;
    }

    private function generateMnemonicPassword(bool $noDashes = false): string
    {
        $words = [];
        $maxWord = count(Wordlist::WORDS) - 1;

        for ($i = 0; $i < 3; $i++) {
            $words[] = mb_convert_case(Wordlist::WORDS[random_int(0, $maxWord)], random_int(MB_CASE_LOWER, MB_CASE_TITLE));
        }

        $words[] = random_int(0, $maxWord % 100);

        shuffle($words);

        $separator = $noDashes ? '' : '-';

        return implode($separator, $words);
    }


    private function scramble(string $password): string {
        $letters = preg_split('//', $password);
        shuffle($letters);
        reset($letters);
        return implode($letters);
    }

}