function entropy(signal,N,r,m,pp,w,delta){
	if(pp > 0)
	   signal = signal.slice(p);

	var tmpM = m;
	var windowCount = N / w;
	
	if(N < 5000){
		for(m = m; m < tmpM+1; m++){
			var xi = [[]];
			//var xi = zeros(N-m+1,m);
			for(var i = 1; i <= N-m+1; i++){
				xi[i] = signal.slince(i,m);
			}
			var Cm = zeros(N-m+1,1); 
			var d = zeros(N-m+1,m);
			for(var j=1; j < N-m+1; j++){  
				var xj = repmat(xi[j],N-m+1,1);     //powtórzenie kazdego wiersza,N-m+1 wierszy, 1 raz
				d = absMLE(xi,xj,r);                //wyliczenie ró¿nicy i wybór wszystkich elementów <=r
				var k = allNZ(d,2);                      //ró¿nica obydwóch wspó³rzêdnych musi byæ <=r
				Cm(j) = sum(k)/(N-m+1);           //liczba podobnych odcinków/wszystkie
			}
			phi(m)=(sum(log(Cm)))/(N-m+1); 
		}
		ApEn=phi(m-1)-phi(m);
	}
}

function zeros(row,column){
	var arr = [[]];
	for(var i = 0; i < row; i++){
		for(var j = 0; j < column; j++)
			arr[i].push(0);
	}
	return arr;
}

function repmat(A,m,n){
	var B = [];
	row = A.length;
	column = A[0].length;
	for(var i = 0; i < m*row; i++)
		for(var j = 0; j < n*column; j++)
			B[i][j] = A[i%row][j%column];
	return B;
}

function absMLE(value1, value2, compare){
	var result = [];
	if(value1.length == value2.length)
		row = value1.length;
	else
		row = 0;
	if(value1[0].length == value2[0].length)
		column = value1[0].length;
	else 
		column = 0;
	for(var i = 0; i < row; i++){
		for(var j = 0; j < column; j++){
			if(abs(value1[i][j]-value2[i][j]) <= compare)
				result[i][j] = 1;
			else
				result[i][j] = 0;
		}
	}
	return result;
}

function allNZ(value, dim){
	var row, column;
	if(dim == 1){
		row = value.length;
		column = value[0].length;
	}
	else{
		row = value[0].length;
		column = value.length;;
	}
	//$result = array_fill(0,$column,1);
	for(var i = 0; i < row; i++){
		for(var j = 0; j < column; j++){
			if(dim == 1){
				if(value[i][j] == 0)
					result[j] = 0;
			}
			else{
				if(value[j][i] == 0)
					result[j] = 0;
			}
		}
	}
	return result;
}