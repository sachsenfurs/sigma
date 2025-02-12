<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => self::randomNickname(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'no', // password
            'remember_token' => Str::random(10),
            'reg_id' => $this->faker->unique()->numberBetween(3,130),
        ];
    }

    public static function randomNickname() {
        $adjectives = [
            "fluffy", "happy", "blue", "big", "small", "fuzzy", "shiny", "swift", "crazy", "wild",
            "cunning", "playful", "silly", "gentle", "grumpy", "cheerful", "mystic", "shadow", "bright", "dark",
            "electric", "neon", "golden", "silver", "frosty", "icy", "stormy", "burning", "fierce", "sleepy",
            "hyper", "jumpy", "curious", "stealthy", "majestic", "floppy", "squeaky", "proud", "goofy", "dizzy",
            "crazy", "loyal", "friendly", "spicy", "noisy", "silent", "furry", "dreamy", "lazy", "snuggly",
            "magical", "striped", "spotted", "sparkly", "zappy", "soft", "chubby", "sleek", "brave", "fast",
            "sneaky", "bouncy", "sweet", "bushy", "quirky", "shaggy", "fancy", "mellow", "boofy", "fluffiest",
            "cozy", "roaring", "chirpy", "whiskered", "purring", "frostbitten", "mysterious", "legendary", "twitchy", "radiant",
            "howling", "glowing", "meowing", "fiercest", "sugary", "vivid", "stripiest", "playfullest", "daring", "cloudy",
            "polkadot", "cuddliest", "sparkliest", "sharp-toothed", "pointy-eared", "masked", "feathery", "fluffball", "whimsical", "whippy"
        ];

        $species = [
            "Fox", "Wolf", "Tiger", "Folf", "Dragon", "Dog", "Cat", "Bunny", "Deer", "Raccoon",
            "Otter", "Hyena", "Cheetah", "Leopard", "Panda", "Husky", "Shepherd", "Ferret", "Bat", "Kangaroo",
            "Lynx", "Horse", "Cougar", "Jackal", "Coyote", "Rat", "Mouse", "Squirrel", "Skunk", "Red Panda",
            "Dingo", "Chimera", "Bear", "Goat", "Lion", "Snow Leopard", "Griffin", "Shark", "Dolphin", "Raptor",
            "Gryphon", "Lizard", "Phoenix", "Weasel", "Cobra", "Viper", "Frog", "Moose", "Zebra", "Chinchilla",
            "Marten", "Eagle", "Hawk", "Falcon", "Owl", "Raven", "Crow", "Jay", "Toad", "Foxbat",
            "Dragonwolf", "Basilisk", "Liger", "Wolfdog", "Jackalope", "Werewolf", "Cervine", "Wyvern", "Drake", "Feral",
            "Mutant", "Demonfox", "Cyberwolf", "Cybercat", "Kitsune", "Tanuki", "Basilisk", "Pegasus", "Hippogriff", "Unicorn",
            "Sabertooth", "Manticore", "Gargoyle", "Raptorfox", "Foxcoon", "Hybrid", "Furry Alien", "Werecat", "Spacewolf", "Aardwolf",
            "Thylacine", "Okapi", "Sea Dragon", "Gecko", "Komodo", "Axolotl", "Maned Wolf", "Civet", "Opossum", "Platypus"
        ];

        return ucfirst(Arr::random($adjectives)).ucfirst(Arr::random($species));
    }

}
