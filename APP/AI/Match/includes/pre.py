










#
import pandas as pd
import os 
import sys
import pytesseract as tess
import numpy as np
from PIL import Image
from shutil import copyfile
from sklearn.model_selection import train_test_split
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import OrdinalEncoder,StandardScaler,MinMaxScaler
import matplotlib.pyplot as plt
import matplotlib as mpl
from mpl_toolkits.mplot3d import Axes3D
from pandas.plotting import scatter_matrix
from sklearn.linear_model import SGDClassifier
from sklearn.svm import SVC
from sklearn.neighbors import KNeighborsClassifier
import gensim
from gensim.models import Word2Vec
from nltk.tokenize import sent_tokenize, word_tokenize
import warnings
from sklearn.manifold import TSNE
import re
from sklearn.linear_model import LinearRegression
from sklearn.tree import DecisionTreeRegressor
from sklearn.multioutput import MultiOutputClassifier
from sklearn.neural_network import MLPClassifier
from sklearn.ensemble import RandomForestClassifier
import pickle
from itertools import chain
from sklearn.cluster import KMeans
import mysql.connector
from pandas.io import sql
import pymysql
from sqlalchemy import create_engine
import time


tess.pytesseract.tesseract_cmd = r'/usr/local/bin/tesseract'
pd.options.mode.chained_assignment = None  # default='warn'
#class that 
class pre:
	def __init__(self):
		start = time.time()
		#relevanet directories
		self.root = '/var/www/html/Practice-Practice/'
		self.data_dir = self.root+'AI/Match/data/'
		self.images_dir = self.root+'AI/Match/data/images/'
		#program pickles
		self.qs_table_pkl= self.root+'AI/Match/pkl/qs_table.pkl'
		self.qs_table2_pkl= self.root+'AI/Match/pkl/qs_table2.pkl'
		self.qs_table3_pkl= self.root+'AI/Match/pkl/qs_table3.pkl'
		self.qs_table4_pkl= self.root+'AI/Match/pkl/qs_table4.pkl'
		self.qs_table5_afterwtv_pkl= self.root+'AI/Match/pkl/qs_table5.pkl'
		self.label_names_pkl= self.root+'AI/Match/pkl/label_names.pkl'
		self.named_clusters_pkl= self.root+'AI/Match/pkl/named_clusters.pkl'
		self.targets_pkl= self.root+'AI/Match/pkl/targets.pkl'
		self.questions_pkl = self.root+'AI/Match/pkl/questions.pkl'
		self.vocab_mod_pkl = self.root+'AI/Match/pkl/vocab_mod.pkl'
		self.model_pkl = self.root+'AI/Match/pkl/model.pkl'
		self.prd_indexA_pkl = self.root+'AI/Match/pkl/indexA.pkl'
		self.prd_indexB_pkl = self.root+'AI/Match/pkl/indexB.pkl'
		#hyper peramiters
		self.hyp_word_length = 4
		self.hyp_bad_chars = ['_']
		self.hyp_word2vec_word_threshold = 3
		self.hyp_word2vec_epochs = 6
		self.hyp_n_clusters = 63
		self.hyp_test_size = 0.2
		self.hyp_dead_label1 = '25_eidtor_tutorial'
		self.hyp_dead_label2 = '02_question'
		#mysql server load
		server = 'localhost' 
		database = 'site' 
		username = 'root' 
		password = 'Mustafa12211' 
		self.mydb = mysql.connector.connect(
				host=server,
				user=username,
				password=password,
				database=database
		)
		self.engine = create_engine("mysql+pymysql://" + username + ":" +password + "@" +server + "/" +database)
		end = time.time()
		print("init = ")
		print(end - start)
	#
	def load_qs_table(self):
		start = time.time()
		#load dataframe*****************
		self.mycursor = self.mydb.cursor()
		qs = pd.read_sql('SELECT * FROM questions where 1', con=self.mydb)
		clusters = pd.read_sql("select count(*) as count from (SELECT * FROM `points` WHERE 1 and pt_subject = 'Maths' group by pt_moduel,pt_chapter)as A", con=self.mydb)
		#self.hyp_n_clusters = clusters.iloc[0]['count'] - 43
		#basic processing
		self.our_qs =qs[qs.q_origin == 'PP']
		self.qs = qs[qs.q_origin != 'PP']
		end = time.time()
		print("load_qs_table:")
		print(end - start)
	#
	def list_png_files(self):
		start = time.time()
		#loop through all the questions in the data base
		for idd,dirr in self.qs.iterrows():
			loc = self.root+dirr['q_directory']+'/files'
			#use the directory location to find png files
			for root, dirs, files in os.walk(loc, topdown=False):
				i = 1;
				for name in files:
					#copy the png files to the images directory with the correct names
					if name[0] == 'q' or name[0]== 'Q':
						to = self.images_dir+str(idd)+'_'+str(i)+'.png'
						file = os.path.join(root, name)
						if os.path.isfile(to):
							i +=1
						else:
							copyfile(file, to)
							i += 1
		#belongs in list_png_files before optimisation
		end = time.time()
		print("list_png_files:")
		print(end - start)
	#
	def tess_png_to_txt(self):
		start = time.time()
		#try to load the saved model and add it's content to the existing questions dataframe
		try:
			#loading pickle
			temp = pd.read_pickle(self.questions_pkl)
			print(temp)
			self.qs['context'] = temp['context']
			print(self.qs)
		except:
			#convert png to text and trim q_origin
			self.list_png_files()
			array = []
			pct = 1
			for idd,dirr in self.qs.iterrows():
				i = 0
				info = ''
				while i<= 10:
					png = self.images_dir+str(idd)+'_'+str(i)+'.png'
					if os.path.isfile(png):
						img = Image.open(png)
						context = tess.image_to_string(img) 
						info += context
					i+=1
				array.append(info)
				print(pct*100/(len(self.qs)))
				pct += 1
			self.qs['context'] = array
			self.qss = self.qs[['q_id','context']]
			self.qss.to_pickle(self.questions_pkl)
		end = time.time()
		print("tess_png_to_text:")
		print(end - start)
		
	#
	def encode_columns(self):
		start = time.time()
		#encoding the data
		ordinal_encoder = OrdinalEncoder()
		self.qs['label'] = self.qs['q_moduel']+' '+self.qs['q_chapter']
		self.qs_lb_temp = self.qs['label']
		encods = self.qs[["q_origin","q_moduel",'q_chapter','label']]
		encoded_info = ordinal_encoder.fit_transform(encods)	
		encoded_info = pd.DataFrame(encoded_info, columns = ['q_origin','q_moduel','q_chapter','label'])
		self.qs = self.qs.reset_index(drop=True)
		#
		self.qs['q_origin'] = encoded_info['q_origin']
		self.qs['q_moduel'] = encoded_info['q_moduel']
		self.qs['q_chapter'] = encoded_info['q_chapter']
		self.qs['label'] = encoded_info['label']
		self.qs_lb2_temp = self.qs['label']
		#df_concat = 
		#
		self.qs_lb2_temp = self.qs_lb2_temp.reset_index()
		self.qs_lb_temp = self.qs_lb_temp.reset_index()
		#
		self.label_names = pd.concat([self.qs_lb2_temp, self.qs_lb_temp], axis=1)
		self.label_names = self.label_names.drop(['index'],axis = 1)
		self.label_names = self.label_names.drop_duplicates()
		self.label_names.columns = ['encoded','decoded']
		self.label_names2 = pd.DataFrame(self.label_names.decoded.str.split(' ',1).tolist(),columns = ['q_moduel','q_chapter'])
		self.label_names = self.label_names.reset_index()
		self.label_names2 = self.label_names2.reset_index()
		self.label_names = pd.concat([self.label_names, self.label_names2], axis=1)
		self.label_names = self.label_names.drop(['index','decoded'],axis = 1)
		#
		self.hyp_dead_label = self.label_names.loc[(self.label_names['q_moduel'] == self.hyp_dead_label1) & (self.label_names['q_chapter'] == self.hyp_dead_label2)]
		self.hyp_dead_label = self.hyp_dead_label.iloc[0]['encoded']
		end = time.time()
		print("encoded_clumns: ")
		print(end - start)
	#
	def clean_cut_data(self):
		start = time.time()
		#
		dic = []
		#clean and cut data
		for idd,dirr in self.qs.iterrows():
			x = dirr['context']
			for i in self.hyp_bad_chars:
				x = x.replace(i,'')
			x = x.split()
			lis = []

			for y in x:
				if len(y) >=self.hyp_word_length:
					lis.append(y)
			dic.append(lis)
		self.qs['context_arr'] = dic
		self.qs = self.qs[self.qs['context_arr'].map(lambda d: len(d)) > 0]
		self.qs = self.qs.drop(labels=['context'],axis = 1)
		#
		convert_dict = {'q_difficulty': float,
						'q_total_marks': float,
						'q_origin': float,
						'q_chapter': float,
						'q_moduel': float,
						'context_arr': object,
						}
		self.qs = self.qs.astype(convert_dict)
		end = time.time()
		print("clean_cut_data:")
		print(end - start)
		#
	def create_word_to_vec(self):
		start = time.time()
		try:
			#loading pickle
			self.vocab_mod =Word2Vec.load(self.vocab_mod_pkl)
		except:
			self.vocab_mod = Word2Vec(self.qs['context_arr'],min_count=self.hyp_word2vec_word_threshold,vector_size=100)
			self.vocab_mod.train(self.qs['context_arr'],total_examples=self.vocab_mod.corpus_count,epochs = self.hyp_word2vec_epochs)
			self.vocab_mod.save(self.vocab_mod_pkl)
		#
		end = time.time()
		print("create_word_to_vec:")
		print(end - start)
	#
	def apply_word_to_vec(self):
		start = time.time()
		try:
			with open(self.qs_table5_afterwtv_pkl, 'rb') as file:
				temp = pickle.load(file)
			self.qs['context_vec'] = temp['context_vec']
		except:
			#
			dic = []
			words = list(self.vocab_mod.wv.key_to_index)
			#convert arr to vec
			for idd,dirr in self.qs.iterrows():
				x = dirr['context_arr']
				lista = []
				#lista.append(qs['q_origin'][idd])
				for y in x:
					if y in words:
						lista.append(self.vocab_mod.wv[y])

				lista = np.array(lista,dtype=object)
				#lista = np.add.reduce(lista)
				#if np.linalg.norm(lista) > 0:
				#	dic.append(lista)
				#else:
				#	dic.append(np.nan)
				dic.append(lista)
			#
			self.qs['context_vec'] = dic
			#self.qs = self.qs.drop(labels=['context_arr'],axis = 1)
			self.qs = self.qs.dropna()
			with open(self.qs_table5_afterwtv_pkl, 'wb') as file:
				pickle.dump(self.qs, file)
		#
		end = time.time()
		print("apply word to vec:")
		print(end - start)
	#
	def final_format(self):
		start = time.time()
		#
		self.labelled = self.qs[self.qs['label'] != self.hyp_dead_label]
		self.nonlabelled_qs = self.qs[self.qs['label'] == self.hyp_dead_label ]
		self.targets = self.nonlabelled_qs[['q_id','context_vec']]
		self.targets['clusters'] = ''
		self.qs = self.qs.explode('context_vec')
		self.qs = self.qs.dropna()
		end = time.time()
		print("final_format:")
		print(end - start)
	def split_n_train(self):
		start = time.time()
		#
		train_set, test_set = train_test_split(self.qs, test_size=self.hyp_test_size, random_state=42)
		#
		features = self.qs['context_vec']
		#features = features.to_numpy()
		try:
			with open(self.model_pkl, 'rb') as file:
				self.final_model = pickle.load(file)
		except:
			#
			kmeans = KMeans(n_clusters=self.hyp_n_clusters, random_state=0)
			self.final_model = kmeans
			self.final_model.fit(list(features))
			with open(self.model_pkl, 'wb') as file:
				pickle.dump(self.final_model, file)
			#
		end = time.time()
		print("split n train:")
		print(end - start)
		#
	def cluster_identification(self):
		start = time.time()
		#named_clusters ['location','cluster_number']
		self.named_clusters = pd.DataFrame(columns=['label','cluster_number'])
		#
		
		try:
			with open(self.named_clusters_pkl, 'rb') as file:
				self.named_clusters = pickle.load(file)
		except:
			for idd,lista in self.labelled.iterrows():
				if len(lista['context_vec']) >= 1:
					prd = self.final_model.predict(lista['context_vec'])
					prd = np.unique(prd)
					df2 = pd.DataFrame([[lista['label'],prd]], columns=['label','cluster_number'])
					self.named_clusters = self.named_clusters.append(df2)
			with open(self.named_clusters_pkl, 'wb') as file:
				pickle.dump(self.named_clusters, file)
		try:
			with open(self.targets_pkl, 'rb') as file:
				self.targets = pickle.load(file)
		except:
			for idd,lista2 in self.targets.iterrows():
				try:
					if len(lista2['context_vec']) > 1:
						prd = self.final_model.predict(lista2['context_vec'])
						prd = np.unique(prd)
						self.targets.at[idd,'clusters']= prd
				except  Exception as e: 
					print(lista2)
					break
			with open(self.targets_pkl, 'wb') as file:
				pickle.dump(self.targets, file)
		
		self.targets = self.targets.drop(labels = ['context_vec'],axis=1)
		end = time.time()
		print("cluster identification:")
		print(end - start)
	def result_upload(self):
		start = time.time()
		#
		self.label_names.to_sql('AI_label_names', con=self.engine, if_exists='replace')
		self.named_clusters.to_sql('AI_named_clusters', con=self.engine, if_exists='replace')
		self.targets.to_sql('AI_target_predictions', con=self.engine, if_exists='replace')
		end = time.time()
		print("result_upload:")
		print(end - start)
	def predict(self,img_link):
		img = Image.open(img_link)
		x = tess.image_to_string(img) 
		dic = []
		#convert png to text
		for i in self.hyp_bad_chars:
			x = x.replace(i,'')
		x = x.split()
		lis = []
		#
		for y in x:
			if len(y) >=self.hyp_word_length:
				lis.append(y)
		dic.append(lis)

		self.vocab_mod.train(dic,total_examples=self.vocab_mod.corpus_count,epochs = self.hyp_word2vec_epochs)
		words = list(self.vocab_mod.wv.key_to_index)
		#
		x = dic
		lista = []
		dic2 = []
		for y in x[0]:
			if y in words:
				lista.append(self.vocab_mod.wv[y])

		lista = np.array(lista,dtype=object)
		temp = []
		#for word in lista:
		#	temp.append(self.final_model.predict([word,word]))
		prd = self.final_model.predict(lista)
		counts = np.bincount(prd)
		print(np.argmax(counts))
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	
	