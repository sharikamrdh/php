CREATE DATABASE fable_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE fable_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS livres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    auteur VARCHAR(255) NOT NULL,
    editeur VARCHAR(255) NOT NULL,
    date DATE,
    langue VARCHAR(255) NOT NULL,
    genre VARCHAR(255) NOT NULL,
    image_url VARCHAR(255) DEFAULT 'images/defaut.jpg',
    description TEXT
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE INDEX idx_title ON livres (title);

-- nouveau tableau pour les livres lus
CREATE TABLE IF NOT EXISTS statuts_lecture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    statut ENUM('Pile à lire', 'En cours', 'Lu') NOT NULL DEFAULT 'Pile à lire',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY (user_id, livre_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    note INT CHECK (note >= 1 AND note <= 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (user_id, livre_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

ALTER TABLE livres ADD COLUMN resume TEXT;
ALTER TABLE livres ADD COLUMN auteur_bio TEXT;

UPDATE livres
SET resume = "Sarah, une aide-ménagère méticuleuse et solitaire, découvre un lourd secret chez l’un de ses clients. Entre mensonges et manipulations, son quotidien bascule dans un jeu dangereux.",
    auteur_bio = "Freida McFadden est médecin et autrice de thrillers psychologiques à succès. Elle est connue pour ses récits haletants mêlant mystères et émotions, traduits dans plusieurs langues."
WHERE title = "La femme de ménage";

SELECT DISTINCT statut FROM statuts_lecture;

CREATE TABLE bookclubs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    livre_id INT NOT NULL,
    club_nom VARCHAR(255),
    moderateur_id INT,
    FOREIGN KEY (livre_id) REFERENCES livres(id),
    FOREIGN KEY (moderateur_id) REFERENCES users(id)
);

CREATE TABLE bookclub_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    date_joined TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (livre_id) REFERENCES livres(id)
);

SELECT DISTINCT genre FROM livres;

CREATE TABLE messages_club (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    message TEXT NOT NULL,
    date_message TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (livre_id) REFERENCES livres(id)
);

INSERT INTO messages_club (user_id, livre_id, message, date_message)
VALUES (1, 3, 'Ce passage m’a vraiment bouleversé, surtout la scène avec la lettre.', '2025-05-18 11:02:49');

CREATE TABLE commentaires (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    commentaire TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (user_id, livre_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
);

INSERT INTO statuts_lecture (user_id, livre_id, statut)
VALUES (1, 1, 'Lu');

-- 🎯 TABLE DE PROGRESSION DE LECTURE
CREATE TABLE IF NOT EXISTS progression_lecture (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    livre_id INT NOT NULL,
    pages_lues INT DEFAULT 0,
    pourcentage DECIMAL(5,2) DEFAULT 0.00,
    date_mise_a_jour TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
);


-- 🎯 OBJECTIFS DE LECTURE
CREATE TABLE IF NOT EXISTS objectifs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    annee INT NOT NULL,
    nb_livres_objectif INT,
    genres_pref TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- 🏆 STATISTIQUES MENSUELLES
CREATE TABLE IF NOT EXISTS stats_mensuelles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    mois YEAR,
    annee INT,
    livre_id INT,
    nb_lectures INT DEFAULT 0,
    FOREIGN KEY (livre_id) REFERENCES livres(id)
);

-- 🏅 BEST-SELLER ANNUEL
CREATE TABLE IF NOT EXISTS bestseller_annuel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    annee INT,
    livre_id INT,
    nb_lectures INT,
    FOREIGN KEY (livre_id) REFERENCES livres(id)
);

-- 📋 LISTES DE LECTURE PARTAGÉES
CREATE TABLE IF NOT EXISTS listes_partagees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom_liste VARCHAR(255),
    description TEXT,
    createur_id INT,
    date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (createur_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS livres_dans_liste (
    id INT AUTO_INCREMENT PRIMARY KEY,
    liste_id INT,
    livre_id INT,
    FOREIGN KEY (liste_id) REFERENCES listes_partagees(id) ON DELETE CASCADE,
    FOREIGN KEY (livre_id) REFERENCES livres(id) ON DELETE CASCADE
);


INSERT INTO livres (id, title, auteur, editeur, date, langue, genre) VALUES
(1, 'La femme de menage', 'Mcfadden, Freida', 'J\'ai lu', '2023-10-04', 'Français', 'Thriller'),
(2, 'Les secrets de la femme de menage', 'Mcfadden, Freida', 'J\'ai lu', '2024-10-02', 'Français', 'Thriller'),
(3, 'Un avenir radieux', 'Lemaitre, Pierre', 'Calmann-Levy', '2025-01-21', 'Français', 'Roman'),
(4, 'Le pays des autres tome 3 ; j\'emporterai le feu', 'Slimani, Leïla', 'Gallimard', '2025-01-23', 'Français', 'Roman'),
(5, 'La sage-femme d\'Auschwitz', 'Stuart, Anna', 'J\'ai lu', '2024-03-06', 'Français', 'Historique'),
(6, 'Tout le bleu du ciel', 'Da Costa, Mélissa', 'Le livre de poche', '2020-02-12', 'Français', 'Romance'),
(7, 'La femme de menage voit tout', 'Mcfadden, Freida', 'City', '2024-10-02', 'Français', 'Thriller'),
(8, 'Instinct tome 1', 'Inoxtag ; Monnot, Basile ; Compain, Charles', 'Michel Lafon', '2024-11-21', 'Français', 'Aventure'),
(9, 'Mon coeur a déménagé', 'Bussi, Michel', 'Pocket', '2025-01-09', 'Français', 'Romance'),
(10, 'Le tatoueur d\'Auschwitz', 'Morris, Heather', 'J\'ai lu', '2021-01-06', 'Français', 'Historique'),
(11, 'Wicked Game', 'Rigins, Anita', 'Editions Addictives', '2025-01-30', 'Français', 'Romance'),
(12, 'Sans soleil tome 1 ; Disco Inferno', 'Grangé, Jean-Christophe', 'Albin Michel', '2025-01-15', 'Français', 'Policier'),
(13, 'La rose de minuit', 'Riley, Lucinda', 'Charleston', '2025-01-13', 'Français', 'Romance'),
(14, 'Résister', 'Saqué, Salomé', 'Payot', '2024-10-16', 'Français', 'Essai'),
(15, 'Kiara, diamant écorché par le sang tome 3', 'Diaz, Hazel', 'Archipel', '2025-01-23', 'Français', 'Romance'),
(16, 'Journal d\'un prisonnier', 'Goldnadel, Gilles-William', 'Fayard', '2024-11-15', 'Français', 'Essai'),
(17, 'Les armes de la lumière', 'Follett, Ken', 'Le livre de poche', '2025-01-02', 'Français', 'Historique'),
(18, 'Mémoires d\'un expert psychiatre', 'Delcroix, Angélina', 'Hugo Poche', '2024-10-02', 'Français', 'Essai'),
(19, 'Ce que je sais de toi', 'Chacour, Eric', 'Folio', '2025-01-02', 'Français', 'Thriller'),
(20, 'Conte de fées', 'King, Stephen', 'Le livre de poche', '2025-01-15', 'Français', 'Fantastique'),
(21, 'Les juges et l\'assassin : L\'enquête secrète au cœur du pouvoir', 'Davet, Gérard ; Lhomme, Fabrice', 'Flammarion', '2025-01-22', 'Français', 'Essai'),
(22, 'Sans soleil tome 2 ; Le roi des ombres', 'Grangé, Jean-Christophe', 'Albin Michel', '2025-01-15', 'Français', 'Policier'),
(23, 'L\'été d\'avant', 'Gardner, Lisa', 'Le livre de poche', '2025-01-02', 'Français', 'Policier'),
(24, 'Boruto - Two Blue Vortex tome 2', 'Kishimoto, Masashi ; Ikemoto, Mikio', 'Kana', '2025-01-31', 'Français', 'Manga'),
(25, 'La psy', 'Mcfadden, Freida', 'City', '2024-04-17', 'Français', 'Thriller'),
(26, 'Où vont les larmes quand elles sèchent', 'Beaulieu, Baptiste', 'Collection Proche', '2024-10-03', 'Français', 'Romance'),
(27, 'Tata', 'Perrin, Valérie', 'Albin Michel', '2024-09-18', 'Français', 'Romance'),
(28, 'À qui sait attendre', 'Connelly, Michael', 'Calmann-Levy', '2025-01-15', 'Français', 'Policier'),
(29, 'Les femmes du bout du monde', 'Da Costa, Mélissa', 'Le livre de poche', '2024-03-27', 'Français', 'Romance'),
(30, 'The Royal Thorns tome 1 : Insomnia', 'Nemezia, Léa', 'Hugo Roman', '2025-01-15', 'Français', 'Romance'),
(31, 'Répondre à la nuit', 'Ledig, Agnès', 'Albin Michel', '2025-01-29', 'Français', 'Romance'),
(32, 'La cité aux murs incertains', 'Murakami, Haruki', 'Belfond', '2025-01-02', 'Français', 'Fantastique'),
(33, 'Les filles de Birkenau', 'Teboul, David ; Choko, Isabelle ; Elkán-Hervé, Judith ; Kolinka, Ginette ; Sénot, Esther', 'Les Arènes', '2025-01-23', 'Français', 'Historique'),
(34, 'L\'enragé', 'Chalandon, Sorj', 'Le livre de poche', '2025-01-29', 'Français', 'Romance'),
(35, 'Le pays des autres t.1', 'Slimani, Leïla', 'Folio', '2021-05-06', 'Français', 'Roman'),
(36, 'Mortelle Adèle ; Sur les traces du Croquepote', 'Mr Tan ; Le Feyer, Diane', 'Mr Tan and Co', '2024-10-17', 'Français', 'Bande dessinée'),
(37, 'Dernière soirée', 'Gardner, Lisa', 'Albin Michel', '2025-01-02', 'Français', 'Policier'),
(38, 'Après Dieu', 'Malka, Richard', 'Gallo', '2025-01-08', 'Français', 'Essai'),
(39, 'Twisted tome 1 ; Twisted Love', 'Huang, Ana', 'Hugo Poche', '2025-01-02', 'Français', 'Romance'),
(40, 'Le silence et la colère', 'Lemaitre, Pierre', 'Le livre de poche', '2024-04-24', 'Français', 'Roman'),
(41, 'Changer l\'eau des fleurs', 'Perrin, Valérie', 'Le livre de poche', '2019-04-24', 'Français', 'Romance'),
(42, 'L\'étranger', 'Camus, Albert', 'Folio', '1972-01-07', 'Français', 'Roman'),
(43, 'Simplissime ; Les recettes Airfryer / Ninja les + faciles du monde', 'Mallet, Jean-François', 'Hachette Pratique', '2024-10-02', 'Français', 'Cuisine'),
(44, 'Les naufragés du Wager', 'Grann, David', 'Points', '2025-01-03', 'Français', 'Historique'),
(45, 'Memoricide', 'Villiers, Philippe de', 'Fayard', '2024-10-25', 'Français', 'Essai'),
(46, 'Le pays des autres t.2 ; Regardez-nous danser', 'Slimani, Leïla', 'Folio', '2023-05-04', 'Français', 'Roman'),
(47, 'Une âme de cendre et de sang', 'Armentrout, Jennifer L.', 'De Saxus', '2025-01-30', 'Français', 'Fantastique'),
(48, 'Kilomètre zéro', 'Ankaoua, Maud', 'J\'ai lu', '2019-10-02', 'Français', 'Romance'),
(49, 'Si Einstein avait su', 'Aspect, Alain', 'Odile Jacob', '2025-01-08', 'Français', 'Essai'),
(50, 'Post Mortem', 'Tournut, Olivier', 'Fayard', '2024-11-06', 'Français', 'Policier'),
(51, 'Tant que le café est encore chaud', 'Kawaguchi, Toshikazu', 'Le livre de poche', '2022-09-14', 'Français', 'Romance'),
(52, 'Houris', 'Daoud, Kamel', 'Gallimard', '2024-08-15', 'Français', 'Romance'),
(53, 'Art-thérapie ; Coloriages Mystères ; Les grands classiques Disney : Spécial débutants', 'Bal, William', 'Hachette Heroes', '2025-01-22', 'Français', 'Activité'),
(54, 'Les Rugbymen tome 23 ; Cet après-midi, vous avez carte bleue !', 'Béka ; Poupard', 'Bamboo', '2025-01-29', 'Français', 'Bande dessinée'),
(55, 'Son odeur après la pluie', 'Sapin-Defour, Cédric', 'Le livre de poche', '2024-08-21', 'Français', 'Romance'),
(56, 'Anatole Latuile tome 18 ; Un max de surprises !', 'Devaux, Clément ; Muller, Olivier ; Didier, Anne', 'Bayard Jeunesse', '2025-01-08', 'Français', 'Bande dessinée'),
(57, 'Ta promesse', 'Laurens, Camille', 'Gallimard', '2025-01-02', 'Français', 'Romance'),
(58, 'Les morts ont la parole', 'Boxho, Philippe', 'Les 3 As', '2022-06-01', 'Français', 'Essai'),
(59, 'Le grand monde', 'Lemaitre, Pierre', 'Le livre de poche', '2023-01-10', 'Français', 'Roman'),
(60, 'Tenir debout', 'Da Costa, Mélissa', 'Albin Michel', '2024-08-14', 'Français', 'Romance'),
(61, 'À tout jamais', 'Hoover, Colleen', 'Hugo Poche', '2024-01-17', 'Français', 'Romance'),
(62, 'Kaiju n°8 tome 13', 'Matsumoto, Naoya', 'Crunchyroll', '2025-01-08', 'Français', 'Manga'),
(63, 'Maple Hills tome 1 ; Icebreaker', 'Grace, Hannah', 'Hlab', '2025-01-29', 'Français', 'Romance'),
(64, 'La Louisiane', 'Malye, Julia', 'Le livre de poche', '2025-01-02', 'Français', 'Romance'),
(65, 'La librairie des livres interdits', 'Levy, Marc', 'Robert Laffont / Versilio', '2024-11-19', 'Français', 'Romance'),
(66, 'Sur tes traces', 'Coben, Harlan', 'Pocket', '2024-10-03', 'Français', 'Policier'),
(67, 'Atelier créatif ; 200 % Stitch', 'Sivignon, Capucine', 'Hachette Heroes', '2025-01-08', 'Français', 'Activité'),
(68, 'Les quatre accords toltèques : La voie de la liberté personnelle', 'Ruiz, Miguel', 'Jouvence', '2024-08-21', 'Français', 'Essai'),
(69, 'IA : Grand remplacement ou complémentarité ?', 'Ferry, Luc', 'L\'Observatoire', '2025-01-15', 'Français', 'Essai'),
(70, 'Les cinq blessures qui empêchent d\'être soi-même', 'Bourbeau, Lise', 'Pocket', '2013-01-17', 'Français', 'Essai'),
(71, 'Bristol', 'Echenoz, Jean', 'Minuit', '2025-01-02', 'Français', 'Romance'),
(72, 'Une belle vie', 'Grimaldi, Virginie', 'Le livre de poche', '2024-05-02', 'Français', 'Romance'),
(73, 'Chainsaw Man tome 18', 'Fujimoto, Tatsuki', 'Crunchyroll', '2025-01-22', 'Français', 'Manga'),
(74, 'Madelaine avant l\'aube', 'Collette, Sandrine', 'Lattès', '2024-08-21', 'Français', 'Romance'),
(75, 'Antigone', 'Anouilh, Jean', 'Table Ronde', '2016-06-08', 'Français', 'Théâtre'),
(76, 'Millénium 7 : La fille dans les serres de l\'aigle', 'Smirnoff, Karin', 'Actes Sud', '2025-01-02', 'Français', 'Policier'),
(77, 'Jacaranda', 'Faye, Gaël', 'Grasset', '2024-08-14', 'Français', 'Romance'),
(78, 'L\'Alchimiste', 'Coelho, Paulo', 'J\'ai lu', '2021-03-10', 'Français', 'Romance'),
(79, 'Les aventures de Tintin tome 5 ; Le Lotus bleu', 'Hergé', 'Casterman', '2025-01-08', 'Français', 'Bande dessinée'),
(80, 'Jamais plus', 'Hoover, Colleen', 'Hugo Poche', '2018-04-05', 'Français', 'Romance'),
(81, 'Les chats d\'Ulthar', 'Lovecraft, Howard Phillips ; Tanabe, Gou', 'Ki-oon', '2025-01-23', 'Français', 'Horreur'),
(82, 'Les aventures de Buck Danny tome 61 ; Traque en haute altitude', 'Zumbiehl, Frédéric', 'Dupuis', '2025-01-31', 'Français', 'Bande dessinée'),
(83, 'Chevalier Chouette et Petite Oiselle', 'Denise, Christopher', 'Kaleidoscope', '2025-01-22', 'Français', 'Jeunesse'),
(84, 'La mort en face : Le médecin légiste qui fait parler les morts', 'Boxho, Philippe', 'Les 3 As', '2024-08-21', 'Français', 'Essai'),
(85, 'Toxic Hell', 'Rigins, Anita', 'Editions Addictives', '2025-01-30', 'Français', 'Romance'),
(86, 'Angélique', 'Musso, Guillaume', 'Le livre de poche', '2024-03-05', 'Français', 'Romance'),
(87, 'Les sept sœurs t.1 ; Maia', 'Riley, Lucinda', 'Le livre de poche', '2020-06-03', 'Français', 'Romance'),
(88, 'La tresse', 'Colombani, Laetitia', 'Le livre de poche', '2018-05-30', 'Français', 'Romance'),
(89, 'Les douleurs fantômes', 'Da Costa, Mélissa', 'Le livre de poche', '2023-02-01', 'Français', 'Romance'),
(90, 'Parler avec sa mère', 'Rovere, Maxime', 'Flammarion', '2025-01-22', 'Français', 'Romance'),
(91, 'Léonid Petrov tome 1 ; Le prince des enfers', 'Ad, Nanou', 'Eden Editions', '2025-01-22', 'Français', 'Romance'),
(92, 'Mortelle Adèle t.21 ; Récréaction générale !', 'Mr Tan ; Le Feyer, Diane', 'Mr Tan and Co', '2024-05-23', 'Français', 'Bande dessinée'),
(93, 'Le barman du Ritz', 'Collin, Philippe', 'Albin Michel', '2024-04-24', 'Français', 'Romance'),
(94, 'Je revenais des autres', 'Da Costa, Mélissa', 'Le livre de poche', '2022-01-26', 'Français', 'Romance'),
(95, 'Kings of Sin tome 1 ; La colère', 'Huang, Ana', 'Hugo Roman', '2025-01-02', 'Français', 'Romance'),
(96, 'Les guerriers de l\'hiver', 'Norek, Olivier', 'Michel Lafon', '2024-08-29', 'Français', 'Romance'),
(97, 'Atelier créatif ; Les grands classiques tome 11', 'Mariez, Jérémy ; Disney', 'Hachette Heroes', '2024-04-17', 'Français', 'Activité'),
(98, 'Patronyme', 'Springora, Vanessa', 'Grasset', '2025-01-02', 'Français', 'Romance'),
(99, 'Le rêve du jaguar', 'Bonnefoy, Miguel', 'Rivages', '2024-08-21', 'Français', 'Romance'),
(100, 'Champs de bataille : L\'histoire enfouie du remembrement', 'Léraud, Inès ; Van Hove, Pierre', 'Delcourt', '2024-11-20', 'Français', 'Bande dessinée'),
(101, 'Prime Time', 'Chattam, Maxime', 'Albin Michel', '2024-10-30', 'Français', 'Policier'),
(102, 'Personne ne doit savoir', 'McGowan, Claire', 'Hauteville', '2023-05-03', 'Français', 'Policier'),
(103, 'Ce qui nous rend vivants', 'Green, Emma', 'Editions Addictives', '2025-01-02', 'Français', 'Romance'),
(104, 'One Piece - Edition originale t.1 ; Romance Dawn, à l\'aube d\'une grande aventure', 'Oda, Eiichiro', 'Glenat', '2013-07-03', 'Français', 'Manga'),
(105, 'Heureux les fêlés car ils laissent passer la lumière', 'Giordano, Raphaëlle', 'Pocket', '2024-10-17', 'Français', 'Essai'),
(106, 'Vers la beauté', 'Foenkinos, David', 'Folio', '2019-05-02', 'Français', 'Romance'),
(107, 'Les aventures de Lucky Luke d\'après Morris tome 11 ; Un cow-boy sous pression', 'Jul ; Achdé', 'Lucky Comics', '2024-11-15', 'Français', 'Bande dessinée'),
(108, 'La délicatesse', 'Foenkinos, David', 'Folio', '2018-01-04', 'Français', 'Romance'),
(109, 'Okavango', 'Férey, Caryl', 'Folio', '2025-01-02', 'Français', 'Romance'),
(110, 'Les lendemains', 'Da Costa, Mélissa', 'Le livre de poche', '2021-02-03', 'Français', 'Romance'),
(111, 'Le journal d\'Anne Frank', 'Frank, Anne', 'Le livre de poche', '2022-05-25', 'Français', 'Journal'),
(112, 'Que faire des Juifs ?', 'Sfar, Joann', 'Les Arènes BD', '2025-01-16', 'Français', 'Bande dessinée'),
(113, 'Art-thérapie ; Coloriages Mystères ; Best of Nature', 'Disney', 'Hachette Heroes', '2025-01-29', 'Français', 'Activité'),
(114, 'La doublure', 'Da Costa, Mélissa', 'Le livre de poche', '2023-10-11', 'Français', 'Romance'),
(115, 'Le roman de Marceau Miller', 'Miller, Marceau', 'La Martinière', '2025-01-17', 'Français', 'Romance'),
(116, 'Entretien avec un cadavre : Un médecin légiste fait parler les morts', 'Boxho, Philippe', 'Les 3 As', '2023-06-14', 'Français', 'Essai'),
(117, 'Plan comptable général : Liste intégrale des comptes', 'Disle, Charlotte', 'Dunod', '2025-01-15', 'Français', 'Référence'),
(118, 'Les petits Marabout ; Recettes Airfryer', 'Collectif', 'Marabout', '2023-10-04', 'Français', 'Cuisine'),
(119, 'Solo Leveling tome 16', 'Chugong ; Dubu', 'Kbooks', '2024-12-04', 'Français', 'Manga'),
(120, 'La symphonie des monstres', 'Levy, Marc', 'Pocket', '2024-10-17', 'Français', 'Romance'),
(121, 'Bloom tome 1', 'Mikami, Saka', 'Nobi Nobi', '2025-01-22', 'Français', 'Manga'),
(122, 'Ce que je cherche', 'Bardella, Jordan', 'Fayard', '2024-11-09', 'Français', 'Essai'),
(123, 'Trois', 'Perrin, Valérie', 'Le livre de poche', '2022-03-30', 'Français', 'Romance'),
(124, 'Blake et Mortimer tome 30 ; Signe Olrik', 'Sente, Yves ; Julliard, André', 'Blake et Mortimer', '2024-10-31', 'Français', 'Bande dessinée'),
(125, '13 à table !', 'Collette, Sandrine ; Fouchet, Lorraine ; Giébel, Karine ; Giordano, Raphaëlle ; Jacq, Christian ; Collectif', 'Pocket', '2024-11-07', 'Français', 'Recueil'),
(126, 'Mad Majesty t.1', 'Dane, Delinda', 'Hugo Roman', '2024-11-13', 'Français', 'Romance'),
(127, 'Maple Hills tome 3 ; Daydream', 'Grace, Hannah', 'Hlab', '2025-01-29', 'Français', 'Romance'),
(128, 'Art-thérapie ; Coloriages Mystères ; Disney Princesses ; Petites Princesses', 'Karam, Alexandre', 'Hachette Heroes', '2025-01-02', 'Français', 'Activité'),
(129, 'La dernière vague', 'Bietry, Charles', 'Flammarion', '2025-01-29', 'Français', 'Romance'),
(130, 'Les sept sœurs tome 8 ; Atlas, l\'histoire de Pa Salt', 'Riley, Lucinda', 'Le livre de poche', '2024-05-29', 'Français', 'Romance'),
(131, 'Sarah, Susanne et l\'écrivain', 'Reinhardt, Eric', 'Folio', '2025-01-09', 'Français', 'Romance'),
(132, 'Respire ! Le plan est toujours parfait', 'Ankaoua, Maud', 'J\'ai lu', '2022-10-19', 'Français', 'Essai'),
(133, 'Mortelle Adèle t.4 ; J\'aime pas l\'amour !', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2013-06-06', 'Français', 'Bande dessinée'),
(134, 'Les Sisters t.19 ; Ça déménage !', 'Cazenove, Christophe ; William', 'Bamboo', '2024-10-30', 'Français', 'Bande dessinée'),
(135, 'Valentina tome 1', 'Reed, Azra', 'Hugo Roman', '2024-10-09', 'Français', 'Romance'),
(136, 'Vous parler de mon fils', 'Besson, Philippe', 'Julliard', '2025-01-02', 'Français', 'Romance'),
(137, 'Mortelle Adèle t.2 ; L\'enfer, c\'est les autres', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2013-06-06', 'Français', 'Bande dessinée'),
(138, 'Seasons tome 1 ; Un automne pour te pardonner', 'Moncomble, Morgane', 'Hugo Roman', '2023-09-20', 'Français', 'Romance'),
(139, 'Mortelle Adèle t.1 ; Tout ça finira mal', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2013-06-06', 'Français', 'Bande dessinée'),
(140, 'Shin Zero tome 1', 'Bablet, Mathieu ; Singelin, Guillaume', 'Rue de Sèvres', '2025-01-22', 'Français', 'Bande dessinée'),
(141, 'L\'amour ouf', 'Thompson, Neville', '10/18', '2024-09-19', 'Français', 'Romance'),
(142, 'Survivantes', 'Sire, Cédric', 'Michel Lafon', '2025-01-16', 'Français', 'Romance'),
(143, 'Petit pays', 'Faye, Gaël', 'Le livre de poche', '2017-08-23', 'Français', 'Romance'),
(144, 'Les oubliés du dimanche', 'Perrin, Valérie', 'Le livre de poche', '2017-10-04', 'Français', 'Romance'),
(145, 'À contre-sens t.1 ; Noah', 'Ron, Mercedes', 'Le livre de poche Jeunesse', '2021-06-02', 'Français', 'Romance'),
(146, 'Ce que j\'ai vu à Auschwitz : Les cahiers d\'Alter', 'Fajnzylberg, Alter ; Perrin, Alban', 'Seuil', '2025-01-17', 'Français', 'Historique'),
(147, 'À nos cœurs battants', 'Green, Emma', 'Editions Addictives', '2025-01-02', 'Français', 'Romance'),
(148, 'My Happy Marriage tome 5', 'Agitogi, Akumi ; Kosaka, Rito', 'Kurokawa', '2025-01-16', 'Français', 'Manga'),
(149, 'Un soir d\'été', 'Besson, Philippe', 'Pocket', '2025-01-02', 'Français', 'Romance'),
(150, 'Moi, Fadi, le frère volé tome 1 : (1986-1993)', 'Sattouf, Riad', 'Les Livres du Futur', '2024-10-08', 'Français', 'Bande dessinée'),
(151, 'Islander tome 1 ; L\'exil', 'Férey, Caryl ; Rouge, Corentin', 'Glenat', '2025-01-22', 'Français', 'Bande dessinée'),
(152, 'Mortelle Adèle t.3 ; C\'est pas ma faute !', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2013-06-06', 'Français', 'Bande dessinée'),
(153, 'De nos blessures un royaume', 'Josse, Gaëlle', 'Buchet Chastel', '2025-01-09', 'Français', 'Romance'),
(154, 'Bal à Versailles', 'Steel, Danielle', 'Presses de la Cité', '2025-01-02', 'Français', 'Romance'),
(155, 'Les Schtroumpfs tome 37 ; Les Schtroumpfs et la machine à rêver', 'Culliford, Thierry ; Jost, Alain ; Coninck, Jeroen de ; Diaz Vizoso, Miguel', 'Lombard', '2025-01-08', 'Français', 'Bande dessinée'),
(156, 'Les sept sœurs t.2 ; La sœur de la tempête', 'Riley, Lucinda', 'Le livre de poche', '2020-06-17', 'Français', 'Romance'),
(157, 'Lettre d\'une inconnue', 'Zweig, Stefan', 'Folio', '2018-02-01', 'Français', 'Romance'),
(158, 'One Piece - Edition originale tome 108', 'Oda, Eiichiro', 'Glenat', '2024-10-05', 'Français', 'Manga'),
(159, 'Les sept sœurs tome 3 ; La sœur de l\'ombre', 'Riley, Lucinda', 'Le livre de poche', '2020-06-17', 'Français', 'Romance'),
(160, 'Mortelle Adèle t.8 ; Parents à vendre', 'Mr Tan ; Le Feyer, Diane ; Lecloux, Aurélie', 'Bayard Jeunesse', '2014-12-04', 'Français', 'Bande dessinée'),
(161, 'Une âme de cendre et de sang', 'Armentrout, Jennifer L.', 'De Saxus', '2025-01-30', 'Français', 'Fantastique'),
(162, 'One Piece - Edition originale t.2 ; Luffy versus la bande à Baggy !!', 'Oda, Eiichiro', 'Glenat', '2013-07-03', 'Français', 'Manga'),
(163, 'Je cuisine IG bas à l\'Airfryer : 150 recettes croustillantes', 'Collectif', 'Larousse', '2025-01-22', 'Français', 'Cuisine'),
(164, 'On ne badine pas avec l\'amour', 'Musset, Alfred de', 'Hatier', '2024-04-10', 'Français', 'Théâtre'),
(165, 'Les sœurs d\'Auschwitz', 'Morris, Heather', 'J\'ai lu', '2023-01-04', 'Français', 'Historique'),
(166, '50 États d\'Amérique : Un nouveau regard sur les États-Unis', 'Hennette, Guillaume ; Playground Paris', 'Les Arènes', '2025-01-23', 'Français', 'Référence'),
(167, 'Manon Lescaut', 'Abbé Prévost', 'Belin Education', '2022-05-04', 'Français', 'Romance'),
(168, 'Harry Potter tome 1 ; Harry Potter à l\'école des sorciers', 'Rowling, J. K.', 'Gallimard-Jeunesse', '2023-05-25', 'Français', 'Fantastique'),
(169, 'Ajouter de la vie aux jours', 'Julliand, Anne-Dauphine', 'Les Arènes', '2024-10-10', 'Français', 'Essai'),
(170, 'Kiara, diamant écorché par le sang tome 1', 'Diaz, Hazel', 'Archipel', '2024-05-16', 'Français', 'Romance'),
(171, 'Art-thérapie ; Coloriages Mystères ; Disney Princesses ; Princesses', 'Mariez, Jérémy', 'Hachette Pratique', '2021-02-03', 'Français', 'Activité'),
(172, 'Les sept sœurs t.7 ; La sœur disparue', 'Riley, Lucinda', 'Le livre de poche', '2022-05-25', 'Français', 'Romance'),
(173, 'Mortelle Adèle ; Mortelle Adèle et les reliques du chat-lune', 'Mr Tan ; Le Feyer, Diane', 'Mr Tan and Co', '2023-11-02', 'Français', 'Bande dessinée'),
(174, 'Mortelle Adèle t.5 ; Poussez-vous les moches !', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2013-06-06', 'Français', 'Bande dessinée'),
(175, 'Le pouvoir du moment présent ; Guide d\'éveil spirituel', 'Tolle, Eckhart', 'J\'ai lu', '2010-08-28', 'Français', 'Essai'),
(176, 'Anti-stress', 'Kostanek, Lidia', 'Hachette Pratique', '2019-01-23', 'Français', 'Activité'),
(177, 'L\'atelier des sorciers - L\'art des sorciers', 'Shirahama, Kamome', 'Pika', '2025-01-29', 'Français', 'Manga'),
(178, 'Il n\'y a jamais été trop tard', 'Lafon, Lola', 'Stock', '2025-01-08', 'Français', 'Romance'),
(179, 'Le petit prince', 'Saint-Exupéry, Antoine de', 'Folio', '1999-02-23', 'Français', 'Romance'),
(180, 'Plus rien ne pourra me blesser : Maîtrisez votre esprit et défiez le destin', 'Goggins, David', 'Nimrod', '2023-09-28', 'Français', 'Essai'),
(181, 'Mortelle Adèle t.14 ; Prout atomique', 'Mr Tan ; Le Feyer, Diane', 'Bayard Jeunesse', '2018-05-23', 'Français', 'Bande dessinée'),
(182, 'L\'épouvantail', 'Connelly, Michael', 'Le livre de poche', '2025-01-15', 'Français', 'Policier'),
(183, 'Les 5 Terres tome 14 ; « Juste des ennemis »', 'Lewelyn ; Lereculey, Jérôme', 'Delcourt', '2025-01-22', 'Français', 'Bande dessinée'),
(184, 'Les sept sœurs tome 4 ; La sœur à la perle', 'Riley, Lucinda', 'Le livre de poche', '2020-07-01', 'Français', 'Romance'),
(185, 'Seasons t.2 ; Un hiver pour te résister', 'Moncomble, Morgane', 'Hugo Roman', '2024-01-03', 'Français', 'Romance'),
(186, 'Je t\'aime', 'Hargreaves, Roger', 'Hachette Jeunesse', '2019-01-16', 'Français', 'Jeunesse'),
(187, 'La Bible', 'Collectif', 'Ste Biblique de Genève', '2007-08-30', 'Français', 'Religion'),
(188, 'Le défi de Jérusalem', 'Schmitt, Eric-Emmanuel', 'Le livre de poche', '2025-01-29', 'Français', 'Romance'),
(189, 'Mortelle Adèle t.7 ; Pas de pitié pour les nazebroques !', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2014-06-05', 'Français', 'Bande dessinée'),
(190, 'Recettes et récits : Les meilleures recettes sont celles qui se partagent', 'Gaudry, François-Régis', 'Marabout', '2024-11-13', 'Français', 'Cuisine'),
(191, '80 recettes Airfryer : Easy Fry & Grill', 'Collectif', 'Dessain et Tolra', '2025-01-02', 'Français', 'Cuisine'),
(192, 'La conquête de l\'Ouest', 'Bauer, Alain', 'Fayard', '2025-01-22', 'Français', 'Historique'),
(193, 'L\'opportunité de vivre : Ultimes études', 'Comte-Sponville, André', 'PUF', '2025-01-15', 'Français', 'Essai'),
(194, 'Quelqu\'un d\'autre', 'Musso, Guillaume', 'Calmann-Levy', '2024-03-05', 'Français', 'Romance'),
(195, 'Le café où vivent les souvenirs', 'Kawaguchi, Toshikazu', 'Le livre de poche', '2024-09-25', 'Français', 'Romance'),
(196, 'Mortelle Adèle t.6 ; Un talent monstre !', 'Mr Tan ; Miss Prickly ; Chaurand, Rémi', 'Bayard Jeunesse', '2013-09-19', 'Français', 'Bande dessinée'),
(197, 'Cahiers de Douai ; 1re générale & techno, bac de français', 'Rimbaud, Arthur ; Couprie, Alain ; Faerber, Johan', 'Hatier', '2023-04-05', 'Français', 'Scolaire'),
(198, 'Les petits Marabout ; Airfryer : Recettes express ; 70 recettes testées pour vous !', 'Collectif', 'Marabout', '2025-01-02', 'Français', 'Cuisine');

UPDATE livres SET image_url = 'images/femme de menage.jpg' WHERE id = 1;

