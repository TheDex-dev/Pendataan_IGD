# Form Validation Enhancement

## Overview
Enhanced the stepper form validation to trigger on "Selanjutnya" (Next) button clicks with context-aware validation that matches the selected category. The validation now provides detailed feedback and adapts to the dynamic vehicle field requirements.

## Implementation Date
October 2, 2025

---

## Key Improvements

### 1. **Validation Trigger on "Selanjutnya" Button**
Previously, validation only occurred on form submission. Now it validates **before allowing progression** to the next step.

### 2. **Category-Aware Validation**
The vehicle field (Step 3) dynamically adjusts its validation requirements:
- **Ambulans** → "Nama Ambulans" is required
- **Other categories** → "Plat Nomor Kendaraan" is required
- **No category selected** → Field is hidden and not required

### 3. **Detailed Error Messages**
Instead of generic "fill all fields" messages, users now see:
```
Mohon lengkapi field berikut:
• Nama Pengantar
• Nomor HP
```

### 4. **Real-Time Visual Feedback**
- ✅ **Green border** - Valid input
- ❌ **Red border** - Invalid/empty input
- 🔵 **Blue border** - Focused input
- ⚪ **Gray border** - Neutral state

### 5. **Smart Field Visibility**
The vehicle field is hidden by default and only appears after category selection, preventing confusion.

---

## Technical Implementation

### JavaScript Validation Logic

#### Enhanced Next Button Handler
```javascript
$('.next-step').on('click', function() {
    const nextStep = parseInt($(this).data('next'));
    const currentStepElement = $(`#step-${currentStep}`);
    
    // Collect validation errors
    let isValid = true;
    let emptyFields = [];
    
    currentStepElement.find('input[required], select[required]').each(function() {
        const field = $(this);
        const fieldLabel = field.closest('.form-group').find('label').text().trim();
        
        // Remove previous validation
        field.removeClass('is-invalid is-valid');
        
        if (!field.val() || field.val().trim() === '') {
            isValid = false;
            field.addClass('is-invalid');
            emptyFields.push(fieldLabel);
        } else {
            field.addClass('is-valid');
        }
    });
    
    if (isValid) {
        // Clear validation and proceed
        currentStepElement.find('.form-control, .form-select').removeClass('is-invalid is-valid');
        currentStepElement.addClass('d-none');
        $(`#step-${nextStep}`).removeClass('d-none');
        updateStepper(nextStep);
        currentStep = nextStep;
        $('.card-body').animate({ scrollTop: 0 }, 300);
    } else {
        // Show detailed error list
        const fieldList = emptyFields.map(field => `• ${field}`).join('<br>');
        Swal.fire({
            icon: 'warning',
            title: 'Perhatian!',
            html: `<div class="text-start"><p class="mb-2">Mohon lengkapi field berikut:</p>${fieldList}</div>`,
            confirmButtonColor: '#ffc107',
            confirmButtonText: 'Mengerti'
        });
    }
});
```

#### Dynamic Category-Based Validation
```javascript
$('#kategori_pengantar').on('change', function() {
    const category = $(this).val();
    const labelText = $('#kendaraan_label_text');
    const icon = $('#kendaraan_icon');
    const inputIcon = $('#kendaraan_input_icon i');
    const input = $('#nama_ambulans');
    const helpText = $('#kendaraan_help_text');
    const formGroup = input.closest('.form-group');
    
    if (category === 'Ambulans') {
        // Configure for Ambulans
        labelText.html('<i class="fas fa-ambulance me-1"></i>Nama Ambulans');
        icon.removeClass('fa-id-card').addClass('fa-ambulance');
        inputIcon.removeClass('fa-id-card').addClass('fa-ambulance');
        input.attr('placeholder', 'Masukkan nama ambulans');
        input.attr('name', 'nama_ambulans');
        helpText.text('Contoh: Ambulans Gawat Darurat 1');
        input.prop('required', true);
        formGroup.show();
    } else if (category) {
        // Configure for other categories (Plat Nomor)
        labelText.html('<i class="fas fa-id-card me-1"></i>Plat Nomor Kendaraan');
        icon.removeClass('fa-ambulance').addClass('fa-id-card');
        inputIcon.removeClass('fa-ambulance').addClass('fa-id-card');
        input.attr('placeholder', 'Masukkan plat nomor kendaraan');
        input.attr('name', 'plat_nomor');
        helpText.text('Contoh: B 1234 XYZ');
        input.prop('required', true);
        formGroup.show();
    } else {
        // Hide if no category
        input.prop('required', false);
        formGroup.hide();
    }
    
    // Clear validation state when category changes
    input.removeClass('is-invalid is-valid').val('');
});
```

#### Real-Time Validation Feedback
```javascript
// Validate on input/change (only in current step)
$(document).on('input change', 'input[required], select[required]', function() {
    const field = $(this);
    const currentStepElement = $(`#step-${currentStep}`);
    
    if (currentStepElement.find(field).length > 0) {
        field.removeClass('is-invalid is-valid');
        
        if (field.val() && field.val().trim() !== '') {
            field.addClass('is-valid');
        } else if (field.closest('.form-group').hasClass('was-validated')) {
            field.addClass('is-invalid');
        }
    }
});

// Validate on blur (when user leaves field)
$(document).on('blur', 'input[required], select[required]', function() {
    const field = $(this);
    const currentStepElement = $(`#step-${currentStep}`);
    
    if (currentStepElement.find(field).length > 0) {
        field.closest('.form-group').addClass('was-validated');
        
        if (!field.val() || field.val().trim() === '') {
            field.addClass('is-invalid');
        } else {
            field.removeClass('is-invalid').addClass('is-valid');
        }
    }
});
```

#### Initialize Hidden Vehicle Field
```javascript
$(document).ready(function() {
    // Hide vehicle field initially
    $('#nama_ambulans').closest('.form-group').hide();
    $('#nama_ambulans').prop('required', false);
    
    // ... rest of initialization
});
```

---

## CSS Validation Styles

### Light Mode Validation
```css
/* Valid State */
.form-control.is-valid,
.form-select.is-valid {
    border-color: #1cc88a;
    background-color: #f0fff4;
}

.form-control.is-valid:focus,
.form-select.is-valid:focus {
    border-color: #1cc88a;
    box-shadow: 0 0 0 3px rgba(28, 200, 138, 0.1);
}

/* Invalid State */
.form-control.is-invalid,
.form-select.is-invalid {
    border-color: #dc3545;
    background-color: #fff5f5;
}

.form-control.is-invalid:focus,
.form-select.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}
```

### Dark Mode Validation
```css
[data-bs-theme="dark"] .form-control.is-valid,
[data-bs-theme="dark"] .form-select.is-valid {
    border-color: #1cc88a;
    background-color: rgba(28, 200, 138, 0.1);
}

[data-bs-theme="dark"] .form-control.is-invalid,
[data-bs-theme="dark"] .form-select.is-invalid {
    border-color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
}
```

---

## Validation Flow

### Step-by-Step Process

#### **Step 1: Kategori**
1. User selects category from dropdown
2. On "Selanjutnya" click:
   - ✅ Check if category is selected
   - ❌ If empty → Show error message
   - ✅ If valid → Proceed to Step 2

#### **Step 2: Data Pengantar**
1. User fills name and phone
2. Real-time validation:
   - Green border on valid input
   - Red border on empty blur
3. On "Selanjutnya" click:
   - ✅ Check both fields are filled
   - ❌ If any empty → Show which fields need completion
   - ✅ If valid → Proceed to Step 3

#### **Step 3: Kendaraan** (Dynamic)
1. Field label/icon changes based on Step 1 category:
   - **Ambulans** → "Nama Ambulans" 🚑
   - **Others** → "Plat Nomor Kendaraan" 🪪
2. Field is required for all categories
3. On "Selanjutnya" click:
   - ✅ Check vehicle info is filled
   - ❌ If empty → Show appropriate field name
   - ✅ If valid → Proceed to Step 4

#### **Step 4: Data Pasien**
1. User fills patient name and gender
2. Both fields required
3. On "Selanjutnya" click:
   - ✅ Validate both fields
   - ❌ If any empty → Show missing fields
   - ✅ If valid → Proceed to Step 5

#### **Step 5: Foto**
1. User uploads photo
2. File selection updates display
3. On "Kirim Data" click:
   - ✅ Check photo is selected
   - ✅ Validate all previous steps data
   - 📤 Submit to server

---

## Form Submission Enhancement

### Updated FormData Construction
```javascript
reader.onload = function(e) {
    const formData = new FormData();
    const category = $('#kategori_pengantar').val();
    
    // Basic fields
    formData.append('kategori_pengantar', category);
    formData.append('nama_pengantar', $('#nama_pengantar').val());
    formData.append('nomor_hp', $('#nomor_hp').val());
    formData.append('nama_pasien', $('#nama_pasien').val());
    formData.append('jenis_kelamin_pasien', $('#jenis_kelamin_pasien').val());
    
    // Dynamic vehicle field
    const vehicleInput = $('#nama_ambulans');
    const vehicleValue = vehicleInput.val();
    
    if (category === 'Ambulans' && vehicleValue) {
        formData.append('nama_ambulans', vehicleValue);
    } else if (category && vehicleValue) {
        // Send plat nomor as nama_ambulans for backend compatibility
        formData.append('nama_ambulans', vehicleValue);
    }
    
    // Image data
    formData.append('foto_pengantar_base64', e.target.result);
    formData.append('foto_pengantar_info[name]', file.name);
    formData.append('foto_pengantar_info[size]', file.size);
    formData.append('foto_pengantar_info[type]', file.type);
    
    submitFormData(formData, submitBtn);
};
```

---

## Validation States

### Visual States

| State | Border Color | Background | Icon | When Applied |
|-------|--------------|------------|------|--------------|
| **Neutral** | Gray (#e2e8f0) | Light gray (#f8fafc) | None | Initial state |
| **Focused** | Blue (#0ea5e9) | Light gray | None | User clicks field |
| **Valid** | Green (#1cc88a) | Light green (#f0fff4) | ✓ | Field has valid value |
| **Invalid** | Red (#dc3545) | Light red (#fff5f5) | ✗ | Field is empty/invalid |

### Validation Triggers

1. **On Input** - Removes invalid state if field becomes valid
2. **On Blur** - Marks field as invalid if empty
3. **On Next Click** - Validates all required fields in current step
4. **On Category Change** - Resets vehicle field validation

---

## Error Handling

### Validation Error Message
```javascript
Swal.fire({
    icon: 'warning',
    title: 'Perhatian!',
    html: `
        <div class="text-start">
            <p class="mb-2">Mohon lengkapi field berikut:</p>
            • Kategori Pengantar<br>
            • Nama Pengantar<br>
            • Nomor HP
        </div>
    `,
    confirmButtonColor: '#ffc107',
    confirmButtonText: 'Mengerti'
});
```

### Empty Fields Collection
```javascript
let emptyFields = [];

currentStepElement.find('input[required], select[required]').each(function() {
    const field = $(this);
    const fieldLabel = field.closest('.form-group').find('label').text().trim();
    
    if (!field.val() || field.val().trim() === '') {
        emptyFields.push(fieldLabel);
    }
});

// Display in alert
const fieldList = emptyFields.map(field => `• ${field}`).join('<br>');
```

---

## User Experience Benefits

### Before Enhancement
❌ Users could skip steps without filling fields  
❌ Generic error message on submit  
❌ No visual feedback during input  
❌ Vehicle field always visible  
❌ Unclear which fields need completion  

### After Enhancement
✅ Must complete current step before proceeding  
✅ Specific list of missing fields shown  
✅ Real-time green/red border feedback  
✅ Vehicle field hidden until category selected  
✅ Clear indication of what needs to be filled  

---

## Validation Rules by Step

### Step 1: Kategori
```javascript
Required: kategori_pengantar
Type: Select dropdown
Options: Ambulans, Karyawan, Perorangan, Satlantas
Validation: Must not be empty
```

### Step 2: Data Pengantar
```javascript
Required: 
  - nama_pengantar (text input)
  - nomor_hp (tel input)
  
Validation:
  - Both fields must not be empty
  - nama_pengantar: Any non-empty text
  - nomor_hp: Numeric values only (filtered on input)
```

### Step 3: Kendaraan (Dynamic)
```javascript
Required: nama_ambulans (dynamic field)

If Ambulans selected:
  - Label: "Nama Ambulans"
  - Icon: fa-ambulance
  - Placeholder: "Masukkan nama ambulans"
  - Example: "Ambulans Gawat Darurat 1"
  
If Other category selected:
  - Label: "Plat Nomor Kendaraan"
  - Icon: fa-id-card
  - Placeholder: "Masukkan plat nomor kendaraan"
  - Example: "B 1234 XYZ"
  
Validation: Must not be empty (only if category selected)
```

### Step 4: Data Pasien
```javascript
Required:
  - nama_pasien (text input)
  - jenis_kelamin_pasien (select)
  
Validation:
  - Both fields must not be empty
  - jenis_kelamin_pasien options: Laki-laki, Perempuan
```

### Step 5: Foto
```javascript
Required: foto_pengantar (file input)

Validation:
  - File must be selected
  - Accept: image/* (any image format)
  - Shows file name when selected
```

---

## Testing Scenarios

### Test Case 1: Empty Field Validation
1. Click "Selanjutnya" without filling category
2. Expected: Alert showing "Kategori Pengantar" is missing
3. Field should have red border

### Test Case 2: Partial Field Completion
1. Fill category, move to step 2
2. Fill only name, leave phone empty
3. Click "Selanjutnya"
4. Expected: Alert showing "Nomor HP" is missing
5. Phone field should have red border

### Test Case 3: Dynamic Field Validation
1. Select "Ambulans" category
2. Move to step 3
3. Verify label shows "Nama Ambulans"
4. Leave empty and click "Selanjutnya"
5. Expected: Alert showing field is missing

### Test Case 4: Category Change
1. Select "Ambulans", fill vehicle field
2. Go back to step 1
3. Change to "Karyawan"
4. Return to step 3
5. Expected: Label changed to "Plat Nomor Kendaraan", field cleared

### Test Case 5: Real-Time Feedback
1. Focus on any input field
2. Type some text
3. Expected: Green border appears
4. Clear the field
5. Click outside (blur)
6. Expected: Red border appears

### Test Case 6: Complete Flow
1. Complete all steps with valid data
2. Each step should allow progression
3. No validation errors should appear
4. Form should submit successfully

---

## Accessibility Improvements

### ARIA Labels (Future Enhancement)
```html
<input 
    aria-required="true"
    aria-invalid="false"
    aria-describedby="nama_pengantar_help"
>
```

### Error Announcements (Future Enhancement)
```javascript
// Screen reader announcement
const announcement = document.createElement('div');
announcement.setAttribute('role', 'alert');
announcement.setAttribute('aria-live', 'polite');
announcement.textContent = 'Mohon lengkapi field: Nama Pengantar';
```

---

## Browser Compatibility

✅ Chrome 90+  
✅ Firefox 88+  
✅ Safari 14+  
✅ Edge 90+  
✅ Mobile browsers (iOS Safari, Chrome Mobile)

---

## Performance Considerations

### Validation Debouncing
Currently validates immediately on blur. For very large forms, consider debouncing:

```javascript
let validationTimeout;
field.on('input', function() {
    clearTimeout(validationTimeout);
    validationTimeout = setTimeout(() => {
        validateField(field);
    }, 300);
});
```

### Efficient jQuery Selectors
Using `$('#step-${currentStep}')` limits validation to current visible step only, improving performance.

---

## Future Enhancements

### Planned Improvements
1. **Field-Level Error Messages** - Show text below each invalid field
2. **Progressive Validation** - Validate as user types (with debounce)
3. **Regex Validation** - Phone number format, plat nomor format
4. **Custom Validation Rules** - Email, URL, date format
5. **Async Validation** - Check if phone number already exists
6. **Validation Summary** - Show all errors at once
7. **Keyboard Shortcuts** - Enter to proceed, Escape to go back
8. **Auto-Save Draft** - Save incomplete forms to localStorage

---

## Conclusion

The enhanced validation system provides a robust, user-friendly experience that:
- ✅ Prevents invalid data entry
- ✅ Provides clear, actionable feedback
- ✅ Adapts to selected category
- ✅ Validates at appropriate times
- ✅ Maintains excellent UX with visual feedback

Users can now confidently complete the form step-by-step with immediate guidance when corrections are needed.
