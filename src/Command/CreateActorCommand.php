<?php

namespace App\Command;

use App\Entity\Actor;
use App\Repository\ActorRepository;

//use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;

/*#[AsCommand(
    name: 'app:create-actor',
    description: 'Add a short description for your command',
)]
*/

class CreateActorCommand extends Command
{

    protected static $defaultName = 'app:create-actor'; //nombre del comando

    //PROPIEDADES
    private $entityManager;
    private $peliculaRepository;

    //CONSTRUCTOR
    public function __construct(EntityManagerInterface $em, ActorRepository $pr){
        parent::__construct();
        $this->entityManager = $em;
        $this->peliculaRepository = $pr;
    }

    protected function configure(): void
    {
        $this->setDescription('Este comando nos permite crear actores')
            ->setHelp('Los parámetros son nombre (REQUERIDO, fecha de nacimiento, nacionalidad y biografía')
            ->addArgument('nom', InputArgument::REQUIRED, 'nom')
            ->addArgument('datadenaixement', InputArgument::OPTIONAL, 'Fecha de nacimiento')
            ->addArgument('nacionalitat', InputArgument::OPTIONAL, 'Nacionalitat')
            ->addArgument('biografia', InputArgument::OPTIONAL, 'Biografia') 
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<fg=white;bg=black>Crear actor</>');

        $titulo = $input->getArgument('nom'); //recuperar datos
        $duracion = $input->getArgument('datadenaixement');
        $director = $input->getArgument('nacionalitat');
        $genero = $input->getArgument('biografia'); 
       
        $actor = new Actor(); //crear y guardar el actor
        $actor->setNom($titulo)->setDatadenaixement($duracion)
            ->setNacionalitat($director)->setBiografia($genero);

        $this->entityManager->persist($actor);
        $this->entityManager->flush();

        $output->writeln("<fg=white;bg=green>Actor $titulo creado!</>");
        return Command::SUCCESS;
    }
}
