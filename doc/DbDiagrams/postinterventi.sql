-- Inserimento utenti
INSERT INTO `HilfeDb`.`User` (`idUser`, `Name`, `Surname`, `PhoneNumber`, `Email`, `Salt`, `Password`, `Bio`, `Birthday`, `PubKey`, `FotoProfilo`, `Username`)
VALUES
(1, 'Mario', 'Rossi', '123456789', 'mario@email.com', 'salt123', 'password123', 'Bio di Mario', '1990-01-01', 'pubkey123', null, 'mario_username'),
(2, 'Luigi', 'Verdi', '987654321', 'luigi@email.com', 'salt456', 'password456', 'Bio di Luigi', '1995-05-05', 'pubkey456', null, 'luigi_username'),
(3, 'Francesca', 'Bianchi', '555555555', 'francesca@email.com', 'salt789', 'password789', 'Bio di Francesca', '1988-10-15', 'pubkey789', null, 'francesca_username'),
(4, 'Alessandro', 'Neri', '111111111', 'alessandro@email.com', 'salt111', 'password111', 'Bio di Alessandro', '1985-07-20', 'pubkey111', null, 'alessandro_username'),
(5, 'Giulia', 'Ricci', '222222222', 'giulia@email.com', 'salt222', 'password222', 'Bio di Giulia', '1992-03-12', 'pubkey222', null, 'giulia_username'),
(6, 'Stefano', 'Moretti', '333333333', 'stefano@email.com', 'salt333', 'password333', 'Bio di Stefano', '1987-08-27', 'pubkey333', null, 'stefano_username');



-- Inserimento post interventi
INSERT INTO `HilfeDb`.`PostInterventi` (`idPostIntervento`, `TitoloPost`, `DescrizionePost`, `DataIntervento`, `DataPubblicazione`, `PersoneRichieste`, `PosizioneLongitudine`, `PosizioneLatitudine`, `Indirizzo`, `Autore_idUser`)
VALUES
(1, 'Emergenza Alluvione in Via Roma', 'Richiedo l\'aiuto della comunità per affrontare una situazione di alluvione in corso. Abbiamo bisogno di volontari per spostare oggetti, distribuire aiuti e assistere le persone evacuate. L\'intervento è pianificato per domani mattina alle 9:00.', '2023-12-24 09:00:00', '2023-12-23 15:30:00', 10, 41.9027835, 12.4963655, 'Via Roma, 123', 1),
(2, 'Aiuto per anziano bisognoso', 'Sto cercando persone disponibili ad aiutare un anziano residente nella zona. Il compito includerà fare la spesa, pulire la casa e fornire compagnia. Chiunque possa dedicare del tempo, per favore, si faccia avanti.', '2023-12-25 10:30:00', '2023-12-23 16:45:00', 2, 41.8902102, 12.4922311, 'Via Verdi, 456', 2),
(3, 'Supporto dopo il terremoto', 'Dopo l\'ultimo terremoto, molte famiglie hanno bisogno di aiuto. Cerchiamo volontari per distribuire cibo, coperte e fornire sostegno emotivo. L\'intervento è fissato per il prossimo fine settimana.', '2023-12-30 11:00:00', '2023-12-23 17:30:00', 15, 41.9100711, 12.5359979, 'Via Terremoto, 789', 3),
(4, 'Assistenza per malato in isolamento', 'Cerco volontari disposti ad aiutare un malato in isolamento a causa di una malattia contagiosa. Le attività includono la consegna di cibo, farmaci e fornire supporto morale. Grazie in anticipo per il vostro aiuto!', '2023-12-28 14:00:00', '2023-12-23 18:15:00', 5, 41.895464, 12.482324, 'Via Salute, 456', 4),
(5, 'Sostegno psicologico per incidente stradale', 'Sono uno psicologo che offre sostegno psicologico gratuito alle vittime di incidenti stradali. Se hai bisogno di aiuto o conosci qualcuno che potrebbe beneficiarne, non esitare a contattarmi.', '2023-12-26 15:30:00', '2023-12-23 19:00:00', 3, 41.8992265, 12.4958232, 'Via Sostegno, 789', 5);