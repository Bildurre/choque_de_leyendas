import { initFormPage } from '../../common/page-initializers';
import { setupModifiersValidation } from './modifiers-validation';

// Initialize hero class form page
initFormPage({
  customValidation: setupModifiersValidation
});