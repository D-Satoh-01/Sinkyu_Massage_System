//-- public/js/cii-registration.js --//

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
    publicBurdenNumber.disabled = false;
    publicRecipientNumber.disabled = false;
  } else {
    publicBurdenNumber.disabled = true;
    publicRecipientNumber.disabled = true;
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
    localityCodeFamily.disabled = false;
    recipientCodeFamily.disabled = false;
  } else {
    localityCodeFamily.disabled = true;
    recipientCodeFamily.disabled = true;
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
    newInsurerNumber.classList.remove('readonly-field');
    newInsurerName.classList.remove('readonly-field');
    newPostalCode.classList.remove('readonly-field');
    newAddress.classList.remove('readonly-field');
    newRecipientName.classList.remove('readonly-field');
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
    newInsurerNumber.classList.add('readonly-field');
    newInsurerName.classList.add('readonly-field');
    newPostalCode.classList.add('readonly-field');
    newAddress.classList.add('readonly-field');
    newRecipientName.classList.add('readonly-field');
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

// 新規登録郵便番号から住所を検索する関数
async function searchNewAddress() {
  const postalCode = document.getElementById('new_postal_code').value;
  const messageEl = document.getElementById('new-address-message');
  const addressEl = document.getElementById('new_address');

  // 郵便番号を数字のみに変換
  const cleanPostalCode = postalCode.replace(/[^\d]/g, '');

  // 7桁の数字かチェック
  if (cleanPostalCode.length !== 7) {
  return; // エラーメッセージは表示せず、単に終了
  }

  try {
  // 郵便番号API呼び出し（zipcloud API使用）
  const response = await fetch(`https://zipcloud.ibsnet.co.jp/api/search?zipcode=${cleanPostalCode}`);
  const data = await response.json();

  if (data.status === 200 && data.results && data.results.length > 0) {
    const result = data.results[0];

    // 都道府県と市区町村を設定
    addressEl.value = result.address1 + result.address2 + result.address3;
  } else {
    // 該当する住所が無い場合は空欄
    addressEl.value = '';
  }

  } catch (error) {
  console.error('New address search error:', error);
  }
}

// 新規登録郵便番号入力時の処理
document.addEventListener('DOMContentLoaded', function() {
  const newPostalCodeInput = document.getElementById('new_postal_code');
  if (newPostalCodeInput) {
  newPostalCodeInput.addEventListener('input', function(e) {
    let value = this.value;

    // ハイフンを除去して数字のみに
    const numbersOnly = value.replace(/[^\d]/g, '');

    // 7桁になったら自動で住所検索を実行
    if (numbersOnly.length === 7) {
    // 表示用にハイフンを挿入（123-4567の形式）
    this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);

    // 自動で住所検索を実行
    searchNewAddress();
    } else if (numbersOnly.length > 7) {
    // 7桁を超える場合は7桁でカット
    const truncated = numbersOnly.substring(0, 7);
    this.value = truncated.substring(0, 3) + '-' + truncated.substring(3);
    searchNewAddress();
    } else if (numbersOnly.length <= 3) {
    // 3桁以下の場合はそのまま
    this.value = numbersOnly;
    } else {
    // 4-6桁の場合はハイフンを挿入
    this.value = numbersOnly.substring(0, 3) + '-' + numbersOnly.substring(3);
    }
  });
  }
});
