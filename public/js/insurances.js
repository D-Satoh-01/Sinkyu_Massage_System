//-- public/js/insurances.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  await searchAndFillAddress('new_postal_code', {
    combined: true,
    address: 'new_address'
  });
}

// フォーム表示/非表示
function showInsuranceForm() {
  toggleElementVisibility('insurance-form', true);
}

function hideInsuranceForm() {
  toggleElementVisibility('insurance-form', false);
}

// 医療助成対象チェックボックスでフィールド有効/無効（チェックなしで入力不可）
function toggleMedicalAssistanceFields() {
  toggleFieldsByCheckbox('is_healthcare_subsidized', ['public_funds_payer_code', 'public_funds_recipient_code']);
}

// 利用者との続柄で家族向けフィールドの有効/無効を制御
function toggleFamilyFields() {
  toggleFieldsBySelect('relationship_with_clinic_user', '家族', ['locality_code_family', 'recipient_code_family']);
}

// 保険者番号のバリデーション関数（ラッパー）
function validateInsurerNumberWrapper() {
  return validateInsurerNumber('new_insurer_number', 'insurer_number_warning', 'selected_insurer');
}

// 保険者選択で詳細更新（ラッパー）
function updateInsurerFieldsWrapper() {
  const warningElement = document.getElementById('insurer_number_warning');

  updateInsurerFields('selected_insurer', {
    'new_insurer_number': 'number',
    'new_insurer_name': 'name',
    'new_postal_code': 'postal',
    'new_address': 'address',
    'new_recipient_name': 'recipient'
  });

  // 警告メッセージを非表示
  if (warningElement) {
    warningElement.style.display = 'none';
  }
}

// ページ読み込み時に実行（統合版）
document.addEventListener('DOMContentLoaded', function() {
  // 郵便番号入力設定
  setupPostalCodeInput('new_postal_code', searchAddress);

  // 医療助成対象チェックボックスによるフィールド切り替え関数
  toggleMedicalAssistanceFields();

  // 利用者との続柄によるフィールド切り替え関数
  toggleFamilyFields();

  // 保険者番号入力フィールドにイベントリスナーを追加
  const insurerNumberInput = document.getElementById('new_insurer_number');
  if (insurerNumberInput) {
    insurerNumberInput.addEventListener('input', validateInsurerNumberWrapper);
    // ページ読み込み時にも一度バリデーションを実行（既存の入力値がある場合）
    validateInsurerNumberWrapper();
  }

  // 利用者との続柄の変更リスナーを追加
  const relationship = document.getElementById('relationship_with_clinic_user');
  if (relationship) {
    relationship.addEventListener('change', toggleFamilyFields);
  }

  // 医療助成対象チェックボックスの変更リスナーを追加
  const medicalCheckbox = document.getElementById('is_healthcare_subsidized');
  if (medicalCheckbox) {
    medicalCheckbox.addEventListener('change', toggleMedicalAssistanceFields);
  }

  // 画面更新時に選択された保険者情報をインプットボックスに反映
  const selectedInsurer = document.getElementById('selected_insurer');
  if (selectedInsurer && selectedInsurer.value !== '') {
    updateInsurerFieldsWrapper();
  }
});
