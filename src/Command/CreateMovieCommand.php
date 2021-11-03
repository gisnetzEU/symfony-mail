<?php

namespace App\Command;

use App\Entity\Pelicula;
use App\Repository\PeliculaRepository;

//use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
//use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
//use Symfony\Component\Console\Style\SymfonyStyle;

use Doctrine\ORM\EntityManagerInterface;

/*
#[AsCommand(
    name: 'app:create-movie',
    description: 'Add a short description for your command',
)]
*/

class CreateMovieCommand extends Command
{
    protected static $defaultName = 'app:create-movie'; //nombre del comando

    //PROPIEDADES
    private $entityManager;
    private $peliculaRepository;

    //CONSTRUCTOR
    public function __construct(EntityManagerInterface $em, PeliculaRepository $pr){
        parent::__construct();
        $this->entityManager = $em;
        $this->peliculaRepository = $pr;
    }

    //método para indicar la configuración del comando
    
    protected function configure(): void
    {
        $this->setDescription('Este comando nos permite crear películas')
            ->setHelp('Los parámetros son título (REQUERIDO, duración, director y género')
            ->addArgument('titulo', InputArgument::REQUIRED, 'Título')
            ->addArgument('duracion', InputArgument::OPTIONAL, 'Duración')
            ->addArgument('director', InputArgument::OPTIONAL, 'Director')
            ->addArgument('genero', InputArgument::OPTIONAL, 'Genero')           
        ;
    }

    
    protected function execute(InputInterface $input, OutputInterface $output): int
    {        
        $output->writeln('<fg=white;bg=black>Crear película</>');

        $titulo = $input->getArgument('titulo'); //recuperar datos
        $duracion = $input->getArgument('duracion');
        $director = $input->getArgument('director');
        $genero = $input->getArgument('genero'); 
       
        $peli = new Pelicula(); //crear y guardar la pelicula
        $peli->setTitulo($titulo)->setDuracion($duracion)
            ->setDirector($director)->setGenero($genero);

        $this->entityManager->persist($peli);
        $this->entityManager->flush();

        $output->writeln("<fg=white;bg=green>Película $titulo creada!</>");
        return Command::SUCCESS;
    }
    
}
