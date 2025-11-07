//-- public/js/cii-registration.js --//


// 郵便番号から住所を検索する関数
async function searchAddress() {
  await searchAndFillAddress('new_postal_code', {
    combined: true,
    address: 'new_address'
  });
}

// 新規登録郵便番号入力時の処理
document.addEventListener('DOMContentLoaded', function() {
  setupPostalCodeInput('new_postal_code', searchAddress);
});

// フォーム表示/非表示
function showInsuranceForm() {
  document.getElementById('insurance-form').style.display = 'block';
}

function hideInsuranceForm() {
  document.getElementById('insurance-form').style.display = 'none';
}

// 医療助成対象チェックボックスでフィールド有効/無効（チェックなしで入力不可）
function toggleMedicalAssistanceFields() {
  const checkbox = document.getElementById('is_healthcare_subsidized');
  const publicBurdenNumber = document.getElementById('public_funds_payer_code');
  const publicRecipientNumber = document.getElementById('public_funds_recipient_code');

  // 要素が存在しない場合は処理を終了
  if (!checkbox || !publicBurdenNumber || !publicRecipientNumber) {
    return;
  }

  // チェックボックスの状態に応じて入力フィールドの有効/無効を切り替え
  if (checkbox.checked) {
    publicBurdenNumber.readOnly = false;
    publicRecipientNumber.readOnly = false;
  } else {
    publicBurdenNumber.readOnly = true;
    publicRecipientNumber.readOnly = true;
    // チェックが外されたときにフィールドをクリア
    publicBurdenNumber.value = '';
    publicRecipientNumber.value = '';
  }
}

// 利用者との続柄で家族向けフィールドの有効/無効を制御
function toggleFamilyFields() {
  const relationship = document.getElementById('relationship_with_clinic_user');
  const localityCodeFamily = document.getElementById('locality_code_family');
  const recipientCodeFamily = document.getElementById('recipient_code_family');

  // 要素が存在しない場合は処理を終了
  if (!relationship || !localityCodeFamily || !recipientCodeFamily) {
    return;
  }

  // 利用者との続柄が「家族」の場合のみ有効化
  if (relationship.value === '家族') {
    localityCodeFamily.readOnly = false;
    recipientCodeFamily.readOnly = false;
  } else {
    localityCodeFamily.readOnly = true;
    recipientCodeFamily.readOnly = true;
    // 家族以外の場合は値をクリア
    localityCodeFamily.value = '';
    recipientCodeFamily.value = '';
  }
}

// 保険者番号のバリデーション関数
function validateInsurerNumber() {
  const insurerNumberInput = document.getElementById('new_insurer_number');
  const warningElement = document.getElementById('insurer_number_warning');
  const selectedInsurer = document.getElementById('selected_insurer');

  // 要素が存在しない場合は処理を終了
  if (!insurerNumberInput || !warningElement) {
    console.log('保険者番号フィールドまたは警告要素が見つかりません');
    return true;
  }

  // 保険者が選択されている場合は、新規登録フィールドのバリデーションは不要
  if (selectedInsurer && selectedInsurer.value !== '') {
    warningElement.style.display = 'none';
    return true;
  }

  // 入力値から空白を削除
  const value = insurerNumberInput.value.trim();

  // 数字のみを抽出
  const numbersOnly = value.replace(/[^\d]/g, '');

  console.log('保険者番号バリデーション - 入力値:', value, '数字のみ:', numbersOnly, '桁数:', numbersOnly.length);

  // 桁数チェック
  if (numbersOnly.length === 0) {
    // 入力がない場合は警告を非表示
    warningElement.style.display = 'none';
    return true;
  } else if (numbersOnly.length === 6 || numbersOnly.length === 8) {
    // 6桁または8桁の場合は有効
    warningElement.style.display = 'none';
    return true;
  } else {
    // それ以外の桁数は無効
    console.log('警告メッセージを表示します');
    warningElement.style.display = 'block';
    return false;
  }
}

// ページ読み込み時に実行
document.addEventListener('DOMContentLoaded', function() {
  // 医療助成対象チェックボックスによるフィールド切り替え関数
  toggleMedicalAssistanceFields();

  // 利用者との続柄によるフィールド切り替え関数
  toggleFamilyFields();

  // 保険者番号入力フィールドにイベントリスナーを追加
  const insurerNumberInput = document.getElementById('new_insurer_number');
  if (insurerNumberInput) {
    insurerNumberInput.addEventListener('input', validateInsurerNumber);
    // ページ読み込み時にも一度バリデーションを実行（既存の入力値がある場合）
    validateInsurerNumber();
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
  updateInsurerFields();
  }
});


// 保険者選択で詳細更新
function updateInsurerFields() {
  const select = document.getElementById('selected_insurer');
  const selectedOption = select.options[select.selectedIndex];
  const newInsurerNumber = document.getElementById('new_insurer_number');
  const newInsurerName = document.getElementById('new_insurer_name');
  const newPostalCode = document.getElementById('new_postal_code');
  const newAddress = document.getElementById('new_address');
  const newRecipientName = document.getElementById('new_recipient_name');
  const warningElement = document.getElementById('insurer_number_warning');

  if (select.value === '') {
    // 非選択の場合、入力フォームを有効化してクリア
    newInsurerNumber.readOnly = false;
    newInsurerName.readOnly = false;
    newPostalCode.readOnly = false;
    newAddress.readOnly = false;
    newRecipientName.readOnly = false;
    newInsurerNumber.value = '';
    newInsurerName.value = '';
    newPostalCode.value = '';
    newAddress.value = '';
    newRecipientName.value = '';
    // 警告メッセージを非表示
    if (warningElement) {
      warningElement.style.display = 'none';
    }
  } else {
    // 選択されている場合、情報を表示して読み取り専用化
    newInsurerNumber.readOnly = true;
    newInsurerName.readOnly = true;
    newPostalCode.readOnly = true;
    newAddress.readOnly = true;
    newRecipientName.readOnly = true;
    newInsurerNumber.value = selectedOption.getAttribute('data-number') || '';
    newInsurerName.value = selectedOption.getAttribute('data-name') || '';
    newPostalCode.value = selectedOption.getAttribute('data-postal') || '';
    newAddress.value = selectedOption.getAttribute('data-address') || '';
    newRecipientName.value = selectedOption.getAttribute('data-recipient') || '';
    // 保険者が選択されているため警告メッセージを非表示
    if (warningElement) {
      warningElement.style.display = 'none';
    }
  }
}
