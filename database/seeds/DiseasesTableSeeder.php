<?php

use App\Disease;
use Illuminate\Database\Seeder;

class DiseasesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Disease::create([
            'name'=>'Dengue hemorrágico',
            'image'=>'0.png',
            'video'=>'https://www.youtube.com/embed/m_spiNknXN0',
            'description'=>'El dengue hemorrágicoes una forma más severa del dengue. Esta puede ser fatal si no se reconoce o trata adecuadamente. El dengue hemorrágico es causado por infección con los mismos virus que causan el dengue.'
        ]);

        Disease::create([
            'name'=>'Dengue clásico',
            'image'=>'0.png',
            'video'=>'https://www.youtube.com/embed/m_spiNknXN0',
            'description'=>''
        ]);

        Disease::create([
            'name'=>'Chikungunya',
            'image'=>'0.png',
            'video'=>'https://www.youtube.com/embed/m_spiNknXN0',
            'description'=>'Es conocida además como artritis epidémica chikunguña o fiebre de chikunguña, es una enfermedad producida por el virus de tipo alfavirus del mismo nombre, que se transmite a las personas mediante la picadura de los mosquitos portadores Aedes; tanto el Aedes aegypti como el Aedes albopictus.'
        ]);

        Disease::create([
            'name'=>'Zika',
            'image'=>'0.png',
            'video'=>'https://www.youtube.com/embed/m_spiNknXN0',
            'description'=>'El virus de Zika se transmite a las personas principalmente a través de la picadura de mosquitos infectados del género Aedes, y sobre todo de Aedes aegypti en las regiones tropicales. Los mosquitos Aedes suelen picar durante el día, sobre todo al amanecer y al anochecer, y son los mismos que transmiten el dengue, la fiebre chikungunya y la fiebre amarilla.
            Asimismo, es posible la transmisión sexual, y se están investigando otros modos de transmisión, como las transfusiones de sangre.'
        ]);

        Disease::create([
            'name'=>'Malaria',
            'image'=>'0.png',
            'video'=>'https://www.youtube.com/watch?v=A3O_fmBWnBQ',
            'description'=>'También conocida como paludismo, se trata de una enfermedad potencialmente mortal causada por parásitos que se transmiten al ser humano por la picadura de mosquitos infectados y que se cobra la vida unas 600.000 personas al año que no han tomado el tratamiento profiláctico preventivo. De hecho, en 2012 se registraron 207 millones de casos en el mundo y, de ellos, murieron 627.000, de los que 482.000 eran niños menores de cinco años. Diagnosticada a tiempo, la malaria es curable.'
        ]);

        Disease::create([
            'name'=>'Fiebre Amarilla',
            'image'=>'0.png',
            'video'=>'https://www.youtube.com/watch?v=A3O_fmBWnBQ',
            'description'=>'La fiebre amarilla es una infección viral que transmite un tipo particular de mosquito. La infección es más frecuente en zonas de África y Sudamérica, y afecta a los viajeros y residentes de dichas zonas.
                En los casos más leves, la fiebre amarilla causa fiebre, dolores de cabeza, náuseas y vómitos. Pero la fiebre amarilla puede ser mucho más grave y causar problemas cardiacos, hepáticos y renales además de sangrado (hemorragia). Hasta el 50 por ciento de las personas que padecen la forma más grave de la fiebre amarilla mueren a causa de esta enfermedad.'
        ]);


    }
}
