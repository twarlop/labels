INSERT INTO `sos_settings` (`id`, `skey`, `type`, `input`, `recht`, `title_nl`, `title_fr`, `omschr_nl`, `omschr_fr`, `val_description`, `groep`, `locked`) VALUES 
(NULL, 'label_disclaimer_nl', 'admin', 'text', 'hand', 'Automatische toevoeging nl', 'Addition automatique nl', 'Regel die automatisch aan elk etiket wordt toegevoegd.', 'Cette règle est automatiquement ajouté à chaque étiquette', '', '10', '0'),
(NULL, 'label_disclaimer_fr', 'admin', 'text', 'hand', 'Automatische toevoeging fr', 'Addition automatique fr', 'Regel die automatisch aan elk etiket wordt toegevoegd.', 'Cette règle est automatiquement ajouté à chaque étiquette', '', '10', '0');


INSERT INTO  `sos_settings` (
`id` ,
`skey` ,
`type` ,
`input` ,
`recht` ,
`title_nl` ,
`title_fr` ,
`omschr_nl` ,
`omschr_fr` ,
`val_description` ,
`groep` ,
`locked`
)
VALUES (
NULL ,  'label_type2',  'admin',  'select',  'hand',  'afmeting etiket',  'dimension d\'etiquette',  '',  '',  '',  '10',  ''
), (
NULL ,  'label_taal2',  'admin',  'select',  'hand',  'taal etiket',  'language d\etiquette',  '',  '',  '',  '10',  ''
), (
NULL ,  'label_mode2',  'admin',  'select',  'hand',  'Inhoud etiket',  'Information d\'etiquette',  '',  '',  '',  '10',  ''
);