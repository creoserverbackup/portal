import { extend, localize } from 'vee-validate';
import * as rules from 'vee-validate/dist/rules';
import en from 'vee-validate/dist/locale/en.json';
import nl from 'vee-validate/dist/locale/nl.json';
import de from 'vee-validate/dist/locale/de.json';
import es from 'vee-validate/dist/locale/es.json';
import fr from 'vee-validate/dist/locale/fr.json';
import ru from 'vee-validate/dist/locale/ru.json';

localize({
   en, nl, de, es, fr, ru
});

Object.keys(rules).forEach(rule => {
    extend(rule, {
        ...rules[rule]
    });
});

let LOCALE = localStorage.getItem('locale') || process.env.MIX_VUE_APP_I18N_LOCALE;
localize(LOCALE);
